<?php

namespace App\Http\Controllers;

use App\Models\StaffDepositWithdraw;
use Illuminate\Http\Request;
use App\Http\Requests\StoreStaffTransactionRequest;
use App\Http\Requests\UpdateStaffTransactionRequest;
use App\Models\Staff;
use Illuminate\Support\Facades\DB;
use App\Http\Traits\AccountLogTrait;
use App\Models\Account;
use App\Models\AccountLog;

class StaffDepositWithdrawController extends Controller
{
    use AccountLogTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {



    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreStaffTransactionRequest $request)
    {
        DB::beginTransaction();
        try {

            if ($request->type == "deposit") {
                $staff = Staff::where('id', $request->staff_id)->first();
                $staff->increment('loan', $request->amount);
                Account::find($request->account_id)->increment('amount', $request->amount);
            } else {
                $staff = Staff::where('id', $request->staff_id)->first();

                $staff->decrement('loan', $request->amount);
                Account::find($request->account_id)->decrement('amount', $request->amount);
            }
            $account_transfer = StaffDepositWithdraw::create([

                'type' => $request->type,
                'amount' => $request->amount,
                'account_id' => $request->account_id,
                'staff_id' => $request->staff_id,
                'description' => $request->description,
                'miladi_date' => $request->miladidate,
                'shamsi_date' => $request->shamsi_date,
                'branch_id' => auth()->user()->branch_id,
                'user_id' => auth()->user()->id,
            ]);

            $currentDate = isset($request->shamsi_date) ? $request->shamsi_date : $request->miladi_date;
            $account = Account::find($request->account_id);
            $flag = $this->InsertAccountLog($request->account_id, $request->type, $request->amount, $request->description, $account->amount, 'staff_transfer', $account_transfer->id, $currentDate);


            if ($flag) {
                DB::commit();
                return redirect()->route('staff.statement', ['staff' => $request->staff_id])->with('success', 'staff Transfer stored successfully');
            } else {
                DB::rollBack();
                return redirect()->route('staff.statement', ['staff' => $request->staff_id])->with('error', 'Account Transfer Failed');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            // Handle the exception
            return redirect()->back()->with('error', 'Error creating Account Transfer: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\StaffDepositWithdraw  $staffDepositWithdraw
     * @return \Illuminate\Http\Response
     */
    public function show($ids)
    {


        $accounts = Account::branch()->get();
        return view('staff_transaction.create', compact('accounts','ids'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\StaffDepositWithdraw  $staffDepositWithdraw
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $staff_transactions  = StaffDepositWithdraw::where('id', $id)->with('staff')->first();
        if($staff_transactions->action != 'payment'){
            return redirect()->back()->with('error', 'Only staff deposit/withdraw can be edited from here');
        }

        $staff_id=$staff_transactions->staff_id;

        $accounts = Account::branch()->get();
        return view('staff_transaction.create', compact('staff_transactions','accounts','id','staff_id'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\StaffDepositWithdraw  $staffDepositWithdraw
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateStaffTransactionRequest $request, $id)
    {

        DB::beginTransaction();
        try {
              $staffDepositWithdraw=  StaffDepositWithdraw::find($id);

            if ($staffDepositWithdraw->type == "deposit") {
                $staff = Staff::where('id', $staffDepositWithdraw->staff_id)->first();
                $staff->decrement('loan', $staffDepositWithdraw->amount);
                Account::find($staffDepositWithdraw->account_id)->decrement('amount', $staffDepositWithdraw->amount);
            } else {
                $staff = Staff::where('id', $staffDepositWithdraw->staff_id)->first();
                $staff->increment('loan', $staffDepositWithdraw->amount);
                Account::find($staffDepositWithdraw->account_id)->increment('amount', $staffDepositWithdraw->amount);
            }
            $flag =   $staffDepositWithdraw->update([

                'type' => $request->type,
                'amount' => $request->amount,
                'account_id' => $request->account_id,
                'staff_id' => $request->staff_id,
                'description' => $request->description,
                'miladi_date' => $request->miladidate,
                'shamsi_date' => $request->shamsi_date,
                'branch_id' => auth()->user()->branch_id,
                'updated_by' => auth()->user()->id,
            ]);
            if ($request->type == "deposit") {
                $staff = Staff::where('id', $request->staff_id)->first();
                $staff->increment('loan', $request->amount);
                Account::find($request->account_id)->increment('amount', $request->amount);
            } else {
                $staff = Staff::where('id', $request->staff_id)->first();

                $staff->decrement('loan', $request->amount);
                Account::find($request->account_id)->decrement('amount', $request->amount);
            }
            $currentDate = isset($request->shamsi_date) ? $request->shamsi_date : $request->miladi_date;
            $account = Account::find($request->account_id);
            $log = AccountLog::where(['action_id' => $staffDepositWithdraw->id, 'action' => 'staff_transaction'])->update(['amount'  =>$request->amount,'type'  =>$request->type,'balance'  =>$account->amount]);



            if ($flag) {
                DB::commit();
                return redirect()->route('staff.statement', ['staff' => $request->staff_id])->with('success', 'Account Transfer updated successfully');
            } else {
                DB::rollBack();
                return redirect()->route('staff.statement', ['staff' => $request->staff_id])->with('error', 'Account Transfer Failed');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            // Handle the exception
            return redirect()->route('staff.statement', ['staff' => $request->staff_id])->with('error', 'Error creating Account Transfer: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\StaffDepositWithdraw  $staffDepositWithdraw
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $staffDepositWithdraw= StaffDepositWithdraw::find($id);
        $staff= Staff::where('id', $staffDepositWithdraw->staff_id)->first();
        if ($staffDepositWithdraw->type == "deposit") {
           $staff->decrement('loan', $staffDepositWithdraw->amount);
            Account::find($staffDepositWithdraw->account_id)->decrement('amount', $staffDepositWithdraw->amount);
        } else {
           $staff->increment('loan', $staffDepositWithdraw->amount);

            Account::find($staffDepositWithdraw->account_id)->increment('amount', $staffDepositWithdraw->amount);
        }
        AccountLog::where([
            'action'    => 'staff_transfer',
            'action_id'   => $staffDepositWithdraw->id,
        ])->forceDelete();
       $staffDepositWithdraw->forceDelete();
       return redirect()->back()->with('success', 'Staff Transaction Deleted');
    }
}

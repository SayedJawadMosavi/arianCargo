<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Staff;
use App\Models\StaffSalary;
use Illuminate\Http\Request;
use App\Http\Requests\StoreStaffSalaryRequest;
use App\Http\Requests\UpdateStaffSalaryRequest;
use App\Http\Requests\UpdateStaffTransactionRequest;
use Illuminate\Support\Facades\DB;
use App\Http\Traits\AccountLogTrait;
use App\Models\AccountLog;
use App\Models\StaffDepositWithdraw;

class StaffSalaryController extends Controller
{
    use AccountLogTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $staff_salaries  = StaffSalary::with('staff')->get();
        $accounts = Account::branch()->get();
        return view('staff_salary.index', compact('staff_salaries', 'accounts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $staffs  = Staff::get();
        $accounts = Account::branch()->get();
        return view('staff_salary.create', compact('staffs', 'accounts'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreStaffSalaryRequest $request)
    {
        DB::beginTransaction();
        try {

            $staff = Staff::where('id', $request->staff_id)->first();
            $account = Account::find($request->account_id);

            if ($account->amount < $request->paid) {
                throw new \Exception('paid salary can not be bigger than account');
            }

            Account::find($request->account_id)->decrement('amount', $request->paid);
            if ($request->amount > abs($staff->loan)) {
                return redirect()->route('staff_salary.index')->with('error', 'deduction can not be greater than loan');
            } else {
                if ($staff->loan <= 0) {
                    $staff->increment('loan', $request->amount);
                }
            }
            $staff_salary = StaffSalary::create([

                'staff_id' => $request->staff_id,
                'paid' => $request->paid,
                'payable' => $request->payable,
                'salary' => $request->salary,
                'account_id' => $request->account_id,
                'deduction' => $request->amount,
                'description' => $request->description,
                'miladi_date' => $request->miladi_date,
                'shamsi_date' => $request->shamsi_date,
                'branch_id' => auth()->user()->branch_id,
                'user_id' => auth()->user()->id,
            ]);
            if ($request->amount > 0) {

                $account_transfer = StaffDepositWithdraw::create([
                    'type' => 'deposit',
                    'amount' => $request->amount,
                    'action' => 'staff_salary',
                    'action_id' => $staff_salary->id,
                    'account_id' => $request->account_id,
                    'staff_id' => $request->staff_id,
                    'description' => $request->description,
                    'miladi_date' => $request->miladidate,
                    'shamsi_date' => $request->shamsi_date,
                    'branch_id' => auth()->user()->branch_id,
                    'user_id' => auth()->user()->id,
                ]);
            }
            $currentDate = isset($request->shamsi_date) ? $request->shamsi_date : $request->miladi_date;
            $account = Account::find($request->account_id);
            $flag = $this->InsertAccountLog($request->account_id, 'withdraw', $request->paid, $request->description, $account->amount, 'staff_salary', $staff_salary->id, $currentDate);

            if ($flag) {
                DB::commit();
                return redirect()->route('staff_salary.index')->with('success', 'staff Salary stored successfully');
            } else {
                DB::rollBack();
                return redirect()->route('staff_salary.index')->with('error', 'Account Salary Failed');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            // Handle the exception
            return redirect()->back()->with('error', 'Error creating Account Salary: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\StaffSalary  $staffSalary
     * @return \Illuminate\Http\Response
     */
    public function show(StaffSalary $staffSalary)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\StaffSalary  $staffSalary
     * @return \Illuminate\Http\Response
     */
    public function edit(StaffSalary $staff_salary)
    {

        $staffs  = Staff::get();
        $accounts = Account::branch()->get();
        return view('staff_salary.create', compact('staffs', 'accounts', 'staff_salary'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\StaffSalary  $staffSalary
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateStaffSalaryRequest $request, StaffSalary $staffSalary)
    {

        DB::beginTransaction();
        try {
            $staff = Staff::where('id', $staffSalary->staff_id)->first();

            Account::find($staffSalary->account_id)->increment('amount', $staffSalary->paid);
            if ($request->amount > abs($staff->loan)) {

                return redirect()->route('staff_salary.index')->with('error', 'deductio can not be greater than loan');
            } else {

                if ($staff->loan <= 0) {
                    $staff->decrement('loan', $staffSalary->deduction);
                }
            }

            $flag =   $staffSalary->update([

                'staff_id' => $request->staff_id,
                'paid' => $request->paid,
                'payable' => $request->payable,
                'salary' => $request->salary,
                'account_id' => $request->account_id,
                'deduction' => $request->amount,
                'miladi_date' => $request->miladidate,
                'shamsi_date' => $request->shamsi_date,
                'branch_id' => auth()->user()->branch_id,
                'user_id' => auth()->user()->id,
                'updated_by' => auth()->user()->id,
            ]);
            $staff = Staff::where('id', $request->staff_id)->first();

            Account::find($request->account_id)->decrement('amount', $request->paid);

            if ($request->amount > abs($staff->loan)) {

                return redirect()->route('staff_salary.index')->with('error', 'deductio can not be greater than loan');
            } else {

                if ($staff->loan <= 0) {
                    $staff->increment('loan', $request->amount);
                }
            }
            $currentDate = isset($request->shamsi_date) ? $request->shamsi_date : $request->miladi_date;
            $account = Account::find($request->account_id);
            $log = AccountLog::where(['action_id' => $staffSalary->id, 'action' => 'staff_salary'])->update(['amount'  => $request->amount, 'type'  => 'withdraw', 'balance'  => $account->amount]);
            $log = StaffDepositWithdraw::where(['action_id' => $staffSalary->id, 'action' => 'staff_salary'])->update(['amount'  => $request->amount]);



            if ($flag) {
                DB::commit();
                return redirect()->route('staff_salary.index')->with('success', 'staff Salary Updated successfully');
            } else {
                DB::rollBack();
                return redirect()->route('staff_salary.index')->with('error', 'Account Salary Failed');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            // Handle the exception
            return redirect()->back()->with('error', 'Error creating Account Salary: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\StaffSalary  $staffSalary
     * @return \Illuminate\Http\Response
     */
    public function destroy(StaffSalary $staffSalary)
    {

        $staff = Staff::where('id', $staffSalary->staff_id)->first();
        $staff->decrement('loan', $staffSalary->deduction);
        Account::find($staffSalary->account_id)->increment('amount', $staffSalary->paid);

        AccountLog::where([
            'action'    => 'staff_salary',
            'action_id'   => $staffSalary->id,
        ])->forceDelete();
        StaffDepositWithdraw::where([
            'action'    => 'staff_salary',
            'action_id'   => $staffSalary->id,
        ])->forceDelete();
        $staffSalary->forceDelete();
        return redirect()->back()->with('success', 'Staff Salary Deleted');
    }

    public function getSfaffLoan($data)
    { //used in SELF TRANSFER AJAX REQUEST

        $staff_laon = Staff::where('id', $data)->first();
        return response()->json(['staff_laon' => $staff_laon]);
    }
}

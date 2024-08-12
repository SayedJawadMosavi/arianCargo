<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreShareholderRequest;
use App\Http\Requests\UpdateShareholderRequest;
use App\Http\Traits\AccountLogTrait;
use App\Models\Currency;
use App\Models\ShareHolder;
use Illuminate\Http\Request;
use App\Http\Traits\CurrencyTrait;
use App\Http\Traits\ShareholderLogTrait;
use App\Models\Account;
use App\Models\ShareholderLog;
use Illuminate\Support\Facades\DB;
include "PersianCalendar.php";

class ShareHolderController extends Controller
{
    use CurrencyTrait, ShareholderLogTrait, AccountLogTrait;
    protected $settings;

    public function __construct(Request $request)
    {
        $this->settings = $request->get('settings');
        $this->middleware('permission:shareholder.create', ['only' => ['create', 'store']]);
        $this->middleware('permission:shareholder.edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:shareholder.delete', ['only' => ['destroy']]);
        $this->middleware('permission:shareholder.restore', ['only' => ['restore']]);
        $this->middleware('permission:shareholder.forceDelete', ['only' => ['forceDelete']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $shareHolder = ShareHolder::branch()->get();
        $trashed = ShareHolder::branch()->onlyTrashed()->get();
        return view('shareholder.index', compact('shareHolder', 'trashed'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {


        $accounts = Account::branch()->with('currency')->get();

        return view('shareholder.create', compact('accounts'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreShareholderRequest $request)
    {
        DB::beginTransaction();
        try {
            if (!isset($this->settings->date_type ) && $this->settings->date_type == ''){
                return redirect()->back()->with('error', 'Please set the date_type in Settings');
            }

            $sum = Shareholder::branch()->sum('percentage');
            if($request->percentage + $sum > 100){
                throw new \Exception('فیصدی بالاتر از معیار است');
            }

            if ($this->settings->date_type == 'shamsi') {
                $currentDate = datenow();
            } else {
                $currentDate = date('Y-m-d');
            }

            $shareholder = new ShareHolder();
            $attributes = $request->only($shareholder->getFillable());
            $attributes['user_id'] = auth()->user()->id;
            $attributes['active'] = 1;
            $attributes['branch_id'] = auth()->user()->branch_id;
            $shareholder =  $shareholder->create($attributes);

            $treasuries = $request->account;
            $amount = $request->amount;
            // $description = __('home.shareholder').' '.$request->name;
            $description = $request->description;
            if(isset($treasuries) && count($treasuries) > 0 ){

                foreach($treasuries as $index => $treasury) {
                    $am = $amount[$index];
                    $account = Account::find($treasuries[$index]);
                    $account->increment('amount',$amount[$index]);
                    $flag = $this->InsertAccountLog($treasuries[$index], 'deposit', $amount[$index], $description, $account->amount, 'shareholder', $shareholder->id, $currentDate);

                    // $description =  ' حساب '.$request->name.' افتتاح گردید ';
                    if($am <>0 && !is_null($am)){

                        $am > 0 ? $type = 'deposit' : $type = 'withdraw';
                        $curr = $this->GetShareholderCurrency($shareholder->id, $account->currency_id, $am);

                        $this->InsertShareholderLog($shareholder->id, $curr->id, $type, abs($am), $description, $curr->amount, 'share_holder', null, $currentDate);
                    }
                }
            }
            $shareholders = ShareHolder::branch()->get();
            $trashed = ShareHolder::branch()->onlyTrashed()->get();

            if ($shareholder) {
                DB::commit();
                return redirect()->route('shareholder.index', compact('shareholders', 'trashed'))->with('success', 'shareholder stored successfully');
            } else {
                DB::rollBack();
                return redirect()->route('shareholder.index')->with('error', 'shareholder Failed');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            // Handle the exception
            return redirect()->back()->with('error', 'Error creating shareholder: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ShareHolder  $shareHolder
     * @return \Illuminate\Http\Response
     */
    public function show(ShareHolder $shareHolder)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ShareHolder  $shareHolder
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $accounts = Account::with('currency')->get();

        $shareholder= ShareHolder::find($id);

        $currencies = Currency::active()->get();
        return view('shareholder.create', compact('shareholder', 'currencies', 'accounts'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ShareHolder  $shareHolder
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateShareholderRequest $request, ShareHolder $shareholder)
    {

        DB::beginTransaction();
        try {
            $sum = Shareholder::branch()->sum('percentage');
            if($request->percentage + $sum > 100){
                throw new \Exception('فیصدی بالاتر از معیار است');
            }

            $active = isset($request->active) ? 1 : 0;
            // dd($active);
            $attributes = $request->only($shareholder->getFillable());
            $attributes['updated_by'] = auth()->user()->id;
            $attributes['active'] = $active;
            $attributes['branch_id'] = auth()->user()->branch_id;
            $shareholder =  $shareholder->update($attributes);

            $shareholders = ShareHolder::branch()->get();
            $trashed = ShareHolder::branch()->onlyTrashed()->get();
            if ($shareholder) {
                DB::commit();
                return redirect()->route('shareholder.index', compact('shareholders', 'trashed'))->with('success', 'shareholder updated successfully');
            } else {
                DB::rollBack();
                return redirect()->route('shareholder.index')->with('error', 'shareholder Failed');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            // Handle the exception
            return redirect()->back()->with('error', 'Error creating shareholder: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ShareHolder  $shareHolder
     * @return \Illuminate\Http\Response
     */
    public function destroy(ShareHolder $shareHolder)
    {
        //
    }

    public function statement($id){
      $shareholder=  ShareHolder::find($id);

        if ($this->settings->date_type == 'shamsi') {
            $from =  datenow();
            $to =  datenow();
            $column = 'shamsi_date';
        }else {
            $from = date("Y-m-d");
            $to = date("Y-m-d");
            $column = 'miladi_date';
        }

        $logs = ShareholderLog::where('share_holder_id', $shareholder->id)->with('shareholder')->whereBetween($column, [$from, $to])->get();

        return view('shareholder.statement', compact('logs', 'shareholder'));

    }

    public function getStatement($shareholder, Request $request){
        $shareholder=  ShareHolder::find($shareholder);

        if ($this->settings->date_type == 'shamsi') {
            $to = $request->to_shamsi;
            $from = $request->from_shamsi;
            $column = 'shamsi_date';
        } else {
            $to = $request->to_miladi;
            $from = $request->from_miladi;
            $column = 'miladi_date';
        }

        $logs = ShareholderLog::where('share_holder_id', $shareholder->id)->with('shareholder')->whereBetween($column, [$from, $to])->get();
        return view('shareholder.statement', compact('logs', 'shareholder'));

    }

}

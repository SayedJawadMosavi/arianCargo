<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreVendorRequest;
use App\Http\Requests\UpdateVendorRequest;
use App\Http\Traits\CurrencyTrait;
use App\Http\Traits\VendorLogTrait;
use App\Models\Currency;
use App\Models\Setting;
use App\Models\Vendor;
use App\Models\VendorCurrency;
use App\Models\VendorLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;


class VendorController extends Controller
{
    use CurrencyTrait, VendorLogTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $vendors = Vendor::branch()->get();
        $trashed = Vendor::branch()->onlyTrashed()->get();
        return view('vendors.index', compact('vendors', 'trashed'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $currencies = Currency::active()->get();
        return view('vendors.create', compact('currencies'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreVendorRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreVendorRequest $request)
    {
        DB::transaction(function () use ($request, &$vendor){

            $vendor = new Vendor();
            $attributes = $request->only($vendor->getFillable());
            $attributes['user_id'] = auth()->user()->id;
            $attributes['active'] = 1;
            $attributes['branch_id'] = auth()->user()->branch_id;
            $vendor =  $vendor->create($attributes);

            $treasuries = $request->treasury;
            $amount = $request->amount;
            $base_currency =  Setting::where('branch_id', auth()->user()->branch_id)->first();
            $branch_base = $base_currency->currency_id; //
            $curr = $this->GetVendorCurrency($vendor->id, $branch_base, 0);
            // if(isset($treasuries) && count($treasuries) > 0 ){

            //     foreach($treasuries as $index => $treasury) {
            //         $am = $amount[$index];
            //         $description =  ' حساب '.$request->name.' افتتاح گردید ';
            //         if($am <>0 && !is_null($am)){

            //             $am > 0 ? $type = 'paid' : $type = 'received';
            //             $curr = $this->GetVendorCurrency($vendor->id, $treasury, $am);

            //             $this->InsertVendorLog($vendor->id, $curr->id, $type, abs($am), $description, $curr->amount, 'vendor', null, $request->issue_date);
            //         }
            //     }
            // }

        });
        $vendors = Vendor::branch()->get();
        $trashed = Vendor::branch()->onlyTrashed()->get();
        if($request->type == 'purchase'){
            return redirect()->back()->with('Vendor registered successfully');
        }
        return redirect()->route('vendors.index', compact('vendors', 'trashed'))->with('Vendor registered successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Vendor  $vendor
     * @return \Illuminate\Http\Response
     */
    public function show(Vendor $vendor)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Vendor  $vendor
     * @return \Illuminate\Http\Response
     */
    public function edit(Vendor $vendor)
    {
        $currencies = Currency::active()->get();
        return view('vendors.create', compact('vendor', 'currencies'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateVendorRequest  $request
     * @param  \App\Models\Vendor  $vendor
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateVendorRequest $request, Vendor $vendor)
    {
        $active = isset($request->active) ? 1 : 0;
        $attributes = $request->only($vendor->getFillable());
        $attributes['updated_by'] = auth()->user()->id;
        $attributes['active'] = $active;
        $attributes['branch_id'] = auth()->user()->branch_id;
        $vendor =  $vendor->update($attributes);

        $vendors = Vendor::branch()->get();
        $trashed = Vendor::branch()->onlyTrashed()->get();
        return view('vendors.index', compact('vendors', 'trashed'))->with('success', 'Vendor updated sucessfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Vendor  $vendor
     * @return \Illuminate\Http\Response
     */
    public function destroy(Vendor $vendor)
    {
        //
    }

    public function statement(Vendor $vendor){
        // dd($accountclient
        $VendorCurrency=  VendorCurrency::where('vendor_id', $vendor->id)->get();
        $logs = VendorLog::where('vendor_id', $vendor->id)->with('vendor', 'vendor_currency.currency')->get();
        return view('vendors.statement', compact('logs', 'vendor','VendorCurrency'));

    }
    public function filterStatment(Request $request)
    {
        $from = isset($request->from_shamsi) ? $request->from_shamsi : $request->from_miladi;
        $to = isset($request->to_shamsi) ? $request->to_shamsi : $request->to_miladi;
        $column = isset($request->from_shamsi) ? $column = 'shamsi_date' : $column = 'miladi_date';

        $VendorCurrency=  VendorCurrency::where('vendor_id', $request->vendor_id)->get();
        $logs = VendorLog::where('vendor_id', $request->vendor_id)->with('vendor')->whereBetween($column, [$from, $to])->latest()->get();
        $vendor = Vendor::with('currency')->find($request->vendor_id);
        $vendor->vendor_logs = $logs;

        return view('vendors.statement', compact('logs','vendor', 'VendorCurrency'));
    }
}

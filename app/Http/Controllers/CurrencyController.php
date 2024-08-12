<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCurrencyRequest;
use App\Http\Requests\UpdateCurrencyRequest;
use App\Models\Currency;

class CurrencyController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:currency.view', ['only' => ['index', 'statement']]);
        $this->middleware('permission:currency.create', ['only' => ['create', 'store']]);
        $this->middleware('permission:currency.edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:currency.delete', ['only' => ['destroy']]);
        $this->middleware('permission:currency.restore', ['only' => ['restore']]);
        $this->middleware('permission:currency.forceDelete', ['only' => ['forceDelete']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $currencies = Currency::branch()->get();
        return view('currency.index', compact('currencies'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('currency.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreCurrencyRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCurrencyRequest $request)
    {
        $default = isset($request->default) ? 1 : 0;

        $currency = new Currency();
        $attributes = $request->only($currency->getFillable());
        $attributes['user_id'] = auth()->user()->id;
        $attributes['active'] = 1;
        $attributes['default'] = 1;
        $attributes['branch_id'] = auth()->user()->branch_id;
        $currency =  $currency->create($attributes);
        $currencies = Currency::all();
        return redirect()->route('currency.index', compact('currencies'))->with('success', 'Currency created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Currency  $currency
     * @return \Illuminate\Http\Response
     */
    public function show(Currency $currency)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Currency  $currency
     * @return \Illuminate\Http\Response
     */
    public function edit(Currency $currency)
    {
        $currencies = Currency::all();
        return view('currency.index', compact('currencies', 'currency'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateCurrencyRequest  $request
     * @param  \App\Models\Currency  $currency
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCurrencyRequest $request, Currency $currency)
    {
        isset($request->active) ? $active = 1: $active = 0;
        isset($request->default) ? $default = 1: $default = 0;
        $currency->update([
            'name' => $request->name,
            'active' => $active,
            'default' => $default,
            'update_by' => auth()->user()->id
        ]);
        $currencies = Currency::all();
        return redirect()->route('currency.index', compact('currencies'))->with('success', 'Currency updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Currency  $currency
     * @return \Illuminate\Http\Response
     */
    public function destroy(Currency $currency)
    {
        //
    }

    public function changeStatus($id)
    {
        $currency = Currency::find($id);
        try {
            if ($currency->active==1) {
                $currency->update(['active'  =>0]);
            }else if ($currency->active==0) {
                $currency->update(['active'  =>1]);
            }
            return redirect()->route('currency.index')->with('success', 'Currency status updated');
        } catch (\Throwable $th) {
            return redirect()->route('currency.index')->with('error', 'Status update failed');
        }
    }

}

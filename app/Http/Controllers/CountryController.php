<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCountryRequest;
use App\Models\Country;
use Illuminate\Http\Request;

class CountryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $countries = Country::all();
        return view('country.index', compact('countries'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
         return view('country.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCountryRequest $request)
    {
         $default = isset($request->default) ? 1 : 0;

        $country = new Country();
        $attributes = $request->only($country->getFillable());
        $attributes['user_id'] = auth()->user()->id;
        $attributes['active'] = 1;

        $country =  $country->create($attributes);
        $countrys = Country::all();
        return redirect()->route('country.index')->with('success', 'country created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Country  $country
     * @return \Illuminate\Http\Response
     */
    public function show(Country $country)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Country  $country
     * @return \Illuminate\Http\Response
     */
    public function edit(Country $country)
    {
        $countries = Country::all();
        return view('country.index', compact('countries', 'country'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Country  $country
     * @return \Illuminate\Http\Response
     */
    public function update(StoreCountryRequest $request, Country $country)
    {
         isset($request->active) ? $active = 1: $active = 0;
        $country->update([
            'name' => $request->name,
            'description' => $request->description,
            'active' => $active,
        ]);
        $countries = Country::all();
        return redirect()->route('country.index', compact('countries'))->with('success', 'country updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Country  $country
     * @return \Illuminate\Http\Response
     */
    public function destroy(Country $country)
    {
        //
    }
     public function changeStatus($id)
    {
        $country = Country::find($id);
        try {
            if ($country->active==1) {
                $country->update(['active'  =>0]);
                $active = 'country Deactivated';

            }else if ($country->active==0) {
                $country->update(['active'  =>1]);
                $active = 'country Activated';

            }
            return redirect()->route('country.index')->with('success', $active);
        } catch (\Throwable $th) {
            return redirect()->route('country.index')->with('error', 'Status update failed');
        }
    }
}

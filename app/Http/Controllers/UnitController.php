<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUnitRequest;
use App\Http\Requests\UpdateUnitRequest;
use App\Models\Unit;

class UnitController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $units = Unit::all();
        return view('unit.index', compact('units'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('unit.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreunitRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreUnitRequest $request)
    {
        $default = isset($request->default) ? 1 : 0;

        $unit = new Unit();
        $attributes = $request->only($unit->getFillable());
        $attributes['user_id'] = auth()->user()->id;
        $attributes['active'] = 1;
        $attributes['branch_id'] = auth()->user()->branch_id;
        $unit =  $unit->create($attributes);
        $units = Unit::all();
        return redirect()->route('unit.index')->with('success', 'unit created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Unit  $unit
     * @return \Illuminate\Http\Response
     */
    public function show(Unit $unit)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Unit  $unit
     * @return \Illuminate\Http\Response
     */
    public function edit(Unit $unit)
    {
        $units = Unit::all();
        return view('unit.index', compact('units', 'unit'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateUnitRequest  $request
     * @param  \App\Models\Unit  $unit
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUnitRequest $request, Unit $unit)
    {
        isset($request->active) ? $active = 1: $active = 0;
        $unit->update([
            'name' => $request->name,
            'short_name' => $request->short_name,
            'active' => $active,
        ]);
        $units = Unit::all();
        return redirect()->route('unit.index', compact('units'))->with('success', 'unit updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Unit  $unit
     * @return \Illuminate\Http\Response
     */
    public function destroy(Unit $unit)
    {
        //
    }

    public function changeStatus($id)
    {
        $unit = Unit::find($id);
        try {
            if ($unit->active==1) {
                $unit->update(['active'  =>0]);
                $active = 'Unit Deactivated';

            }else if ($unit->active==0) {
                $unit->update(['active'  =>1]);
                $active = 'Unit Activated';

            }
            return redirect()->route('unit.index')->with('success', $active);
        } catch (\Throwable $th) {
            return redirect()->route('unit.index')->with('error', 'Status update failed');
        }
    }
}

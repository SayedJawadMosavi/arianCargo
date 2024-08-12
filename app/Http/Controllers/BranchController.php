<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBranchRequest;
use App\Http\Requests\UpdateBranchRequest;
use App\Models\Branch;
use App\Models\Setting;

class BranchController extends Controller
{
    public function __construct()
    {

        $this->middleware('permission:branch.create', ['only' => ['create', 'store']]);
        $this->middleware('permission:branch.edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:branch.delete', ['only' => ['destroy']]);
        $this->middleware('permission:branch.restore', ['only' => ['restore']]);
        $this->middleware('permission:branch.forceDelete', ['only' => ['forceDelete']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('branch.index')->with('branches',    Branch::get());
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {


        return view('branch.create');

    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreBranchRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreBranchRequest $request)
    {
        $image_path_el="";

        if($request->hasFile('photo')){
            $file = $request->file('photo');
            $filename = time().'_'.$file->getClientOriginalName();
            // File upload location
            $location = 'images/branch/';
            // Upload file
            $image_path=   $file->move($location,$filename);
            $image_path_el = $image_path;

        }

      $user=  Branch::create([
            'name' => $request->name,
            'contact_person' => $request->contact_person,
            'mobile1' => $request->mobile1,
            'mobile2' => $request->mobile2,
            'address' => $request->address,
            'user_id' => auth()->user()->id,
            'logo' => $image_path_el,

        ]);
        $setting = Setting::with('currency')->first();
        $new_setting = $setting->replicate();
        $new_setting->branch_id = $user->id;
        $new_setting->check = 0;
        $new_setting->currency_id = null;
        $new_setting->save();


        return redirect()->route('branch.index')->with('success', 'New Branch added successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Branch  $branch
     * @return \Illuminate\Http\Response
     */
    public function show(Branch $branch)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Branch  $branch
     * @return \Illuminate\Http\Response
     */
    public function edit(Branch $branch)
    {

        return view('branch.create', compact('branch',));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateBranchRequest  $request
     * @param  \App\Models\Branch  $branch
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateBranchRequest $request, Branch $branch)
    {

        $image_path_el="";
        if($request->hasFile('photo')){
            $file = $request->file('photo');
            $filename = time().'_'.$file->getClientOriginalName();
            // File upload location
            $location = 'images/branch/';
            // Upload file
            $image_path=   $file->move($location,$filename);
            $image_path_el = $image_path;

        }


     $branch->update([

        'name' => $request->name,
        'contact_person' => $request->contact_person,
        'mobile1' => $request->mobile1,
        'mobile2' => $request->mobile2,
        'address' => $request->address,
        'user_id' => auth()->user()->id,
        'logo' => $image_path_el,

        ]);

        return redirect()->route('branch.index')->with('success', ' Branch updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Branch  $branch
     * @return \Illuminate\Http\Response
     */
    public function destroy(Branch $branch)
    {
        $branch->delete();
        return redirect()->route('branch.index')->with('success', ' Branch Deleted successfully');

    }
}

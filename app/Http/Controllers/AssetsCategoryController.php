<?php

namespace App\Http\Controllers;

use App\Models\AssetsCategory;
use Illuminate\Http\Request;

class AssetsCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('assets.category.index')->with('categories', AssetsCategory::all());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('assets.category.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([

            'name' => 'required',

        ]);

        AssetsCategory::create([
            'name'=>$request->name,

        ]);
        if($request->type == 'assets'){
            return redirect()->back()->with('Category registered successfully');
        }
        return redirect()->route('asset_category.index')->with('success', 'Assets Category Added successfully');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $assetsCategory=AssetsCategory::find($id);

        return view('assets.category.create')->with('category', $assetsCategory)->with('categories', AssetsCategory::all());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([

            'name' => 'required',

        ]);

        AssetsCategory::where('id',$id)->update([
            'name'=>$request->name,

        ]);
        return redirect()->route('asset_category.index')->with('success', 'Assets Category Updated successfully');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
       AssetsCategory::find($id)->delete();
       return redirect()->route('asset_category.index')->with('success', 'Assets Category Deleted successfully');

    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use App\Models\Setting;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

     public function __construct(){
        $this->middleware('permission:category.view',['only' => ['index']]);
        $this->middleware('permission:category.create',['only' => ['create','store']]);
    }
    public function index()
    {
        $trashed = Category::onlyTrashed()->get();
        $categories = Category::orderBy('id','asc')->get();
        return view('category.index',compact('categories', 'trashed'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('category.create')->with('setting', Setting::first());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreCategoryRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCategoryRequest $request)
    {


        Category::create([
            'name' => $request->name,
            'active' => 1,
            'user_id' => auth()->user()->id,
        ]);
        if($request->type == 'product'){
            return redirect()->back()->with('Category registered successfully');
        }
        return redirect()->route('category.index')->with('success', 'Category added successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        return view('category.create')->with('setting', Setting::first())->with('category', $category);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateCategoryRequest  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $photo = $category->photo;

        if($request->hasFile('photo')){
            @unlink(public_path().'/'.$category->photo);

            $fileName = date('YmdHis').'_'.$request->name.'.'.$request->photo->extension();
            $request->photo->storeAs('storage/images/category', $fileName, 'public');
            $photo = '/storage/images/category/'.$fileName;
            // $img = Image::make($request->file('photo'));

            // $img->resize(350, 350);
            // $img->save('storage/images/category/'.$fileName);
            // $photo = '/storage/images/category/'.$fileName;
        }
        isset($request->active) ? $active = 1: $active = 0;
        $category->update([
            'name' => $request->name,
            // 'photo' => $photo,
            'active' => $active,
            'updated_by' => auth()->user()->id,
        ]);
        return redirect()->route('category.index')->with('success', 'Category updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        $category->update([
            'deleted_by' => auth()->user()->id,
        ]);

        $category->delete();
        return redirect()->route('category.index');
    }

    public function restore(string $id)
    {
        $trashed = Category::onlyTrashed()->get();
        $categories = Category::orderBy('id','desc')->get();
        try {
            Category::withTrashed()->find($id)->restore();
            return view('category.index',compact('categories', 'trashed'))->with('success', 'Category restored');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error',$th->getMessage());
        }

    }

}

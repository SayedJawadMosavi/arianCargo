<?php

namespace App\Http\Controllers;

use App\Models\ExpenseCategory;
use App\Models\Setting;
use App\Http\Requests\ExpenseStoreCategoryRequest;
use App\Http\Requests\ExpenseUpdateCategoryRequest;
use Illuminate\Http\Request;

class ExpenseCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('permission:category.view', ['only' => ['index']]);
        $this->middleware('permission:category.create', ['only' => ['create', 'store']]);
    }
    public function index()
    {
        $trashed = ExpenseCategory::onlyTrashed()->get();
        $categories = ExpenseCategory::orderBy('id', 'desc')->get();

        return view('expense_category.index', compact('categories', 'trashed'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('expense_category.create')->with('setting', Setting::branch()->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ExpenseStoreCategoryRequest $request)
    {


        ExpenseCategory::create([
            'name' => $request->name,
            'type' => $request->type,
            'status' => 1,
            'user_id' => auth()->user()->id,
        ]);
        if($request->types == 'expense_income'){
            return redirect()->back()->with('Category registered successfully');
        }
        return redirect()->route('expense_category.index')->with('success', 'Category added successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ExpenseCategory  $expenseCategory
     * @return \Illuminate\Http\Response
     */
    public function show(ExpenseCategory $expenseCategory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ExpenseCategory  $expenseCategory
     * @return \Illuminate\Http\Response
     */
    public function edit(ExpenseCategory $expenseCategory)
    {
        return view('expense_category.create')->with('setting', Setting::branch()->get())->with('category', $expenseCategory);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ExpenseCategory  $expenseCategory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ExpenseCategory $expenseCategory)
    {

        isset($request->active) ? $active = 1 : $active = 0;
        $expenseCategory->update([
            'name' => $request->name,
            // 'photo' => $photo,
            'status' => $active,
            'updated_by' => auth()->user()->id,
        ]);
        return redirect()->route('expense_category.index')->with('success', 'Category updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ExpenseCategory  $expenseCategory
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        ExpenseCategory::where('id',$id)->delete();
        return redirect()->route('expense_category.index')->with('success', 'Expense Category Deleted');
    }
    public function restore($id)
    {

        ExpenseCategory::where('id', $id)->withTrashed()->restore();

        return redirect()->route('expense_category.index')->with('success', 'Expense Category Restored');
    }
    public function forceDelete($id)
    {


        ExpenseCategory::where('id', $id)->withTrashed()->forceDelete();

        return redirect()->route('expense_category.index')->with('success', 'Expense Category Force Deleted');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\DocumentCategory;
use App\Models\Setting;
use Illuminate\Http\Request;
use App\Http\Requests\DocumentStoreCategoryRequest;
use App\Http\Requests\UpdateDocumentCategoryRequest;
class DocumentCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $trashed = DocumentCategory::onlyTrashed()->get();
        $categories = DocumentCategory::orderBy('id', 'desc')->get();

        return view('document_category.index', compact('categories', 'trashed'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('document_category.create')->with('setting', Setting::first());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(DocumentStoreCategoryRequest $request)
    {
        DocumentCategory::create([
            'name' => $request->name,
            'status' => 1,
            'user_id' => auth()->user()->id,
        ]);
        if($request->types == 'document_type'){
            return redirect()->back()->with('Category registered successfully');
        }
        return redirect()->route('document_category.index')->with('success', 'Category added successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\DocumentCategory  $documentCategory
     * @return \Illuminate\Http\Response
     */
    public function show(DocumentCategory $documentCategory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\DocumentCategory  $documentCategory
     * @return \Illuminate\Http\Response
     */
    public function edit(DocumentCategory $documentCategory)
    {
        return view('document_category.create')->with('setting', Setting::first())->with('category', $documentCategory);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\DocumentCategory  $documentCategory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, DocumentCategory $documentCategory)
    {
        isset($request->active) ? $active = 1 : $active = 0;
        $documentCategory->update([
            'name' => $request->name,
            // 'photo' => $photo,
            'status' => $active,
            'updated_by' => auth()->user()->id,
        ]);
        return redirect()->route('document_category.index')->with('success', 'Category updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\DocumentCategory  $documentCategory
     * @return \Illuminate\Http\Response
     */
    public function destroy(DocumentCategory $documentCategory)
    {
        $documentCategory->delete();
        return redirect()->route('document_category.index')->with('success', 'Document Category Deleted');
    }
    public function restore($id)
    {

        DocumentCategory::where('id', $id)->withTrashed()->restore();

        return redirect()->route('document_category.index')->with('success', 'Document Category Restored');
    }
    public function forceDelete($id)
    {


        DocumentCategory::where('id', $id)->withTrashed()->forceDelete();

        return redirect()->route('document_category.index')->with('success', 'Document Category Force Deleted');
    }
}

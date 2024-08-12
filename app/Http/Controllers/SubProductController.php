<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSubProductRequest;
use App\Http\Requests\UpdateSubProductRequest;
use App\Models\SubProduct;

class SubProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreSubProductRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreSubProductRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SubProduct  $subProduct
     * @return \Illuminate\Http\Response
     */
    public function show(SubProduct $subProduct)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SubProduct  $subProduct
     * @return \Illuminate\Http\Response
     */
    public function edit(SubProduct $subProduct)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateSubProductRequest  $request
     * @param  \App\Models\SubProduct  $subProduct
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateSubProductRequest $request, SubProduct $subProduct)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SubProduct  $subProduct
     * @return \Illuminate\Http\Response
     */
    public function destroy(SubProduct $subProduct)
    {
        //
    }
}

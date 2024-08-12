<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreStockSubProductRequest;
use App\Http\Requests\UpdateStockSubProductRequest;
use App\Models\StockSubProduct;

class StockSubProductController extends Controller
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
     * @param  \App\Http\Requests\StoreStockSubProductRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreStockSubProductRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\StockSubProduct  $stockSubProduct
     * @return \Illuminate\Http\Response
     */
    public function show(StockSubProduct $stockSubProduct)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\StockSubProduct  $stockSubProduct
     * @return \Illuminate\Http\Response
     */
    public function edit(StockSubProduct $stockSubProduct)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateStockSubProductRequest  $request
     * @param  \App\Models\StockSubProduct  $stockSubProduct
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateStockSubProductRequest $request, StockSubProduct $stockSubProduct)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\StockSubProduct  $stockSubProduct
     * @return \Illuminate\Http\Response
     */
    public function destroy(StockSubProduct $stockSubProduct)
    {
        //
    }
}

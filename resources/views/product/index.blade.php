@extends('layouts.app')
@section('title', 'All Products')

@section('content')

<div class="card mt-4">
    @if (session()->has('success') || session()->has('error') )
        @include('layouts.partials.components.alert')
    @endif
    <div class="card-header d-flex justify-content-between">
        <h3 class="card-title">{{ __('home.products') }}</h3>
        @can('product.create')
        <a href="{{ route('product.create') }}" class="btn btn-primary mx-5">{{ __('home.new_product') }}</a>
        @endcan
    </div>

    <div class="card-body pt-4">
        <div class="grid-margin">
            <div class="">
                <div class="panel panel-primary">
                    {{-- <div class="tab-menu-heading tab-menu-heading-boxed">
                        <div class="tabs-menu-boxed">
                            <!-- Tabs -->
                            <ul class="nav panel-tabs product-sale">
                                <li><a href="#tab1" class="active" data-bs-toggle="tab">{{  __('home.all_products') }}</a></li>
                                <li><a href="#tab2" data-bs-toggle="tab" class="text-dark">{{ __('home.trashed') }}</a></li>
                            </ul>
                        </div>
                    </div> --}}
                    <div class="panel-body tabs-menu-body border-0 pt-0">
                        <div class="tab-content">
                            <div class="tab-pane active" id="tab1">
                                <div class="table-responsive">
                                    <table id="file-datatable" class="table table-bordered table-striped text-nowrap mb-0 table-hover">
                                        <thead class="border-top">
                                            <tr>
                                                <th>{{ __('home.sn') }}</th>
                                                <th>{{ __('home.category') }}</th>
                                                <th>{{ __('home.name') }}</th>
                                                <th>{{ __('home.unit') }}</th>
                                                <th>{{ __('home.code') }}</th>
                                                <th>{{ __('home.model') }}</th>
                                                <th>{{ __('home.qty') }}</th>
                                                {{-- <th>{{ __('home.cost') }}</th> --}}
                                                <th>{{ __('home.total') }}</th>
                                                {{-- <th>{{ __('home.sell') }}</th> --}}
                                                <th>{{ __('home.cbm') }}</th>
                                                <th>{{ __('home.dimensions') }}</th>
                                                <th>{{ __('home.action') }}</th>
                                        </thead>
                                        <tbody>
                                            @php $total = 0; $gtotal = 0; @endphp
                                            @foreach($products as $product)
                                            @php $qty=0; @endphp
                                            <tr>
                                                <td>{{$loop->iteration}}</td>
                                                <td>{{$product->category->name ??   ''}}</td>
                                                <td><a href="{{ route('product.stock', $product->id) }}">{{ $product->name }}</a></td>
                                                <td>{{$product->unit->name}}</td>
                                                <td>{{$product->code}}</td>
                                                <td>{{$product->model}}</td>
                                                <td>{{$qty = $product->subProducts->sum('available')}}</td>
                                                <td>{{ number_format($product->grand_total, 2)}}</td>
                                                {{-- <td>{{  MainSubProductTotal($product->grand_total)}}</td> --}}
                                                <td>{{($product->height/100) * ($product->width/100) * ($product->length/100)}}</td>
                                                <td>{{$product->height}} * {{$product->width}} * {{$product->length}}</td>
                                                <td>
                                                    <div class="g-2 ">
                                                    <a class="btn text-success btn-sm" href="{{route('product.show', $product)}}" data-bs-toggle="tooltip" data-bs-original-title="{{ __('home.detail') }}"><span class="fe fe-eye fs-16"></span></a>

                                                        @can('product.edit')
                                                        <a class="btn text-primary btn-sm" href="{{route('product.edit', $product)}}" data-bs-toggle="tooltip" data-bs-original-title="Edit"><span class="fe fe-edit fs-14"></span></a>
                                                        @endcan
                                                        {{-- <a class="btn text-primary btn-sm" href="{{route('product.log', $product)}}" data-bs-toggle="tooltip" data-bs-original-title="{{ __('home.statement') }}"><span class="fe fe-menu fs-14"></span></a> --}}
                                                        @can('product.delete')
                                                        <form action="{{route('product.destroy', $product)}}" method="POST" class="d-none">
                                                            @method('delete')
                                                            @csrf
                                                            <button type="submit" data-bs-toggle="tooltip"  data-bs-original-title="Delete" class="btn text-danger btn-sm"><span class="fe fe-trash-2 fs-14"></span></button>
                                                        </form>
                                                        @endcan
                                                    </div>
                                                </td>
                                            </tr>
                                            @php
                                            $total += $qty;
                                            $gtotal += $product->grand_total;


                                            @endphp
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th></th>
                                                {{-- <th></th> --}}
                                                {{-- <th></th> --}}
                                                {{-- <th></th> --}}
                                                <th>{{ __('home.total') }}</th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th>{{ number_format($total, 2) }}</th>
                                                <th>{{ number_format($gtotal, 2) }}</th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane" id="tab2">
                                <div class="table-responsive">
                                    <table id="file-datatable" class="table table-bordered table-striped text-nowrap mb-0 table-hover">
                                        <thead class="border-top">
                                            <tr>
                                                <th>{{ __('home.no') }}</th>
                                                <th>{{ __('home.name') }}</th>
                                                <th>{{ __('home.model') }}</th>
                                                <th>{{ __('home.category') }}</th>
                                                <th>{{ __('home.cost') }}</th>
                                                <th>{{ __('home.quantity') }}</th>
                                                <th>{{ __('home.action') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($trashed as $product)
                                            <tr class="border-bottom">
                                                <td>{{$product->id}}</td>
                                                <td>{{$product->name}}</td>
                                                <td>{{$product->model}}</td>
                                                <td>{{$product->category_id}}</td>
                                                <td>{{$product->cost}}</td>
                                                <td>{{$product->quantity}}</td>
                                                <td>
                                                    <div class="g-2 ">

                                                        <form action="{{route('product.restore', $product)}}" method="POST" class="d-inline">
                                                            @method('POST')
                                                            @csrf
                                                            <button type="submit" data-bs-toggle="tooltip"  data-bs-original-title="Restore" class="btn text-primary btn-sm"><span class="fe fe-repeat fs-14"></span></button>
                                                        </form>
                                                        <form action="{{route('product.forceDelete', $product)}}" method="POST" class="d-inline">
                                                            @method('delete')
                                                            @csrf
                                                            <button type="submit" data-bs-toggle="tooltip"  data-bs-original-title="Delete Permanently" class="btn text-danger btn-sm"><span class="fe fe-trash-2 fs-14"></span></button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>


                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- card-body --}}
</div>
@endsection

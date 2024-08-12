@extends('layouts.app')
@section('title', 'Sub Products')

@section('content')

<div class="card mt-4">
    @if (session()->has('success') || session()->has('error') )
        @include('layouts.partials.components.alert')
    @endif
    <div class="card-header d-flex justify-content-between">
        <h3 class="card-title">{{ __('home.sub_products') }}</h3>
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
                                                {{-- <th>{{ __('home.purchase') }}</th> --}}
                                                {{-- <th>{{ __('home.date') }}</th> --}}
                                                <th>{{ __('home.category') }}</th>
                                                <th>{{ __('home.name') }}</th>
                                                <th>{{ __('home.model') }}</th>
                                                <th>{{ __('home.available') }}</th>
                                                <th>{{ __('home.cost') }}</th>
                                                <th>{{ __('home.expense') }}</th>
                                                <th>{{ __('home.rent') }}</th>
                                                <th>{{ __('home.income_price') }}</th>
                                                <th>{{ __('home.total') }}</th>
                                                <th>{{ __('home.sell') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php $total = 0; $gtotal = 0; $total_avail=0; $total_income=0; $gtotal=0; @endphp
                                            @foreach($products as $product)
                                            <tr>
                                                <td>{{$loop->iteration}}</td>
                                                {{-- <td>{{$product->purchase->bill}}</td> --}}
                                                {{-- <td> --}}
                                                    {{-- @if ($settings->date_type=='shamsi') --}}
                                                        {{-- {{$product->purchase->shamsi_date}} --}}
                                                    {{-- @else --}}
                                                        {{-- {{$product->purchase->miladi_date}} --}}
                                                    {{-- @endif --}}
                                                {{-- </td> --}}
                                                <td>{{$product->stockProduct->product->category->name ??   ''}}</td>
                                                <td>{{$product->stockProduct->product->name}}</td>
                                                <td>{{$product->stockProduct->product->model}}</td>
                                                <td>{{$product->available}}</td>
                                                <td>{{$product->cost}}</td>
                                                <td>{{$product->expense}}</td>
                                                <td>{{$product->rent}}</td>
                                                <td>{{$product->income_price}}</td>
                                                <td>{{$gtotal = $product->available * $product->income_price}}</td>
                                                <td>{{$product->sell_price}}</td>
                                            </tr>
                                            @php
                                            $total += $product->quantity;
                                            $total_avail += $product->available;
                                            $total_income += $gtotal;
                                            @endphp
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th>{{ __('home.total') }}</th>
                                                <th>{{ number_format($total_avail, 2) }}</th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th>{{ number_format($total_income, 2) }}</th>
                                                <th></th>
                                            </tr>
                                        </tfoot>
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

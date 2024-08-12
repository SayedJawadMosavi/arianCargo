@extends('layouts.app')
@section('title', 'All Products')

@section('content')

<div class="card mt-4">
    @if (session()->has('success') || session()->has('error') )
        @include('layouts.partials.components.alert')
    @endif
    <div class="card-header">
        <h3 class="card-title">{{ __('home.products') }} - {{ $stock->name }} </h3>
    </div>

    <div class="card-body pt-4">
        <div class="grid-margin">
            <div class="">
                <div class="panel panel-primary">
                    <div class="tab-menu-heading tab-menu-heading-boxed">
                        <div class="tabs-menu-boxed">
                            <!-- Tabs -->
                            <ul class="nav panel-tabs product-sale">
                                <li><a href="#tab1" class="active" data-bs-toggle="tab">{{  __('home.all_products') }}</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="panel-body tabs-menu-body border-0 pt-0">
                        <div class="tab-content">
                            <div class="tab-pane active" id="tab1">
                                <div class="table-responsive">
                                    <table id="file-datatable" class="table table-bordered table-striped text-nowrap mb-0 table-hover">
                                        <thead class="border-top">
                                            <tr>
                                                <th>{{ __('home.sn') }}</th>
                                                <th>{{ __('home.category') }}</th>
                                                <th>{{ __('home.product') }}</th>
                                                <th>{{ __('home.code') }}</th>
                                                <th>{{ __('home.unit') }}</th>
                                                <th>{{ __('home.stock') }}</th>
                                                <th>{{ __('home.quantity') }}</th>
                                                <th>{{ __('home.total') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php $total = 0; $qty=0; $gtotal=0; $gtot=0; @endphp
                                            @foreach($products as $log)
                                            <tr>
                                                <td>{{$loop->iteration}}</td>
                                                <td>{{$log->product->category->name}}</td>
                                                <td><a href="{{ route('stock.sub.products', $log->id) }}">{{ $log->product->name }}</a></td>
                                                <td>{{$log->product->code}}</td>
                                                <td>{{$log->product->unit->name}}</td>
                                                <td>{{$log->stock->name}}</td>
                                                <td>{{$qty = $log->subProducts->sum('available')}}</td>
                                                <td>{{$gtot = number_format($log->grand_total)}}</td>

                                            </tr>
                                            @php
                                            $total += $qty;
                                            $gtotal += $log->grand_total;

                                            @endphp
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th>{{ __('home.total') }}</th>
                                                <th>{{ number_format($total) }}</th>
                                                <th>{{ number_format($gtotal) }}</th>
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

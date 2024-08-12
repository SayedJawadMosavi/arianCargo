@extends('layouts.app')
@section('title', 'sells Detail')

@section('content')

<div class="card mt-4">
    @if (session()->has('success') || session()->has('error') )
    @include('layouts.partials.components.alert')
    @endif
    <div class="card-header">
        <h3 class="card-title">{{ __('home.product_detail') }}</h3>


    </div>

    <div class="card-body pt-4">
        <div class="grid-margin">
            <div class="">
                <div class="panel panel-primary">
                    <div class="tab-menu-heading ">
                        <div class="tabs-menu">
                            <!-- Tabs -->
                            <ul class="nav panel-tabs panel-success">
                                <li><a href="#tab1" class="active" data-bs-toggle="tab">{{ __('home.purchases') }}</a></li>
                                <li><a href="#tab2" data-bs-toggle="tab" class="text-dark">{{ __('home.sells') }}</a></li>
                                <li><a href="#tab3" data-bs-toggle="tab" class="text-dark">{{ __('home.returns') }}</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="panel-body tabs-menu-body border-0 p-0">
                        <div class="tab-content">
                            <div class="tab-pane active" id="tab1">
                                <div class="table-responsive">
                                    <table id="file-datatables" class="table table-bordered table-striped text-nowrap mb-0 table-hover">
                                        <thead>
                                            <tr>
                                                <th>{{ __('home.no') }}</th>
                                                <th>{{ __('home.date') }}</th>
                                                <th>{{ __('home.bill') }}</th>
                                                <th>{{ __('home.product') }}</th>
                                                <th>{{ __('home.quantity') }}</th>
                                                <th>{{ __('home.cost') }}</th>
                                                {{-- <th>{{ __('home.sell_price') }}</th> --}}
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @php $c =1; $total_quantity = 0; $total_sell = 0;@endphp
                                            @foreach($purchases as $detail)
                                            <tr>
                                                <td class="d-none">{{$detail->id}}</td>
                                                <td>{{$c++}}</td>
                                                <td>
                                                    @if ($settings->date_type=='shamsi')
                                                    {{$detail->purchase->shamsi_date}}
                                                    @else
                                                    {{$detail->purchase->miladi_date}}
                                                    @endif
                                                </td>
                                                <td>{{$detail->purchase->bill}}</td>
                                                <td>{{$detail->product->name}} - {{$detail->product->model}}</td>
                                                <td>{{$detail->quantity}}</td>
                                                <td>{{$detail->cost}}</td>
                                                {{-- <td>{{$detail->sell_price}}</td> --}}
                                            </tr>
                                            @php
                                            $total_quantity += $detail->quantity;
                                            $total_sell += $detail->sell_price ;

                                            @endphp
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th>{{ __('home.total') }}</th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th>{{ $total_quantity }}</th>
                                                <th></th>
                                                {{-- <th>{{ $total_sell }}</th> --}}
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane" id="tab2">
                                <div class="table-responsive">
                                    <table id="file-datatables" class="table table-bordered table-striped text-nowrap mb-0 table-hover">
                                        <thead>
                                            <tr>
                                                <th>{{ __('home.no') }}</th>
                                                <th>{{ __('home.stock') }}</th>
                                                <th>{{ __('home.product') }}</th>
                                                <th>{{ __('home.quantity') }}</th>
                                                <th>{{ __('home.sell_price') }}</th>
                                                <th>{{__('home.income_price')}}</th>
                                                <th>{{ __('home.cbm') }}</th>
                                                <th>{{ __('home.total') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @php $c =1; $total_quantity = 0; $total=0;$total_cbm = 0;@endphp

                                            @foreach($sells as $sell)
                                            <tr>

                                                <td>{{$c++}}</td>

                                                <td>{{$sell->stock_product->stock->name}}</td>
                                                <td>{{$sell->product->name}} - {{$sell->product->model}}</td>

                                                <td>{{ number_format($sell->quantity) }}</td>
                                                <td>{{ ($sell->cost) }}</td>
                                                <td>{{ ($sell->income_price) }}</td>
                                                <td>{{ number_format($sell->cbm ) }}</td>
                                                <td>{{ number_format($sell->cost * $sell->quantity) }}</td>
                                            </tr>
                                            @php
                                            $total_quantity += $sell->quantity;
                                            $total += $sell->cost * $sell->quantity ;
                                            $total_cbm += $sell->cbm ;


                                            @endphp
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr>

                                                <th>{{ __('home.total') }}</th>
                                                <th></th>
                                                <th></th>
                                                <th>{{ $total_quantity }}</th>
                                                <th></th>

                                                <th></th>
                                                <th>{{ $total_cbm }}</th>
                                                <th>{{ $total }}</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane" id="tab3">
                                <div class="table-responsive">
                                    <table id="file-datatables" class="table table-bordered table-striped text-nowrap mb-0 table-hover">
                                        <thead>
                                            <tr>
                                            <th>{{ __('home.sn') }}</th>
                                                <th>{{ __('home.date') }}</th>
                                                <th>{{ __('home.sell_id') }}</th>
                                                <th>{{ __('home.product') }}</th>
                                                <th>{{ __('home.unit') }}</th>
                                                <th>{{ __('home.quantity') }}</th>
                                                <th>{{ __('home.cost') }}</th>
                                                <th>{{ __('home.description') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @php $total_quantity = 0; $total_cost = 0;@endphp
                                        @foreach($returns as $return)
                                            <tr>
                                                <td>{{$loop->iteration}}</td>
                                                <td>
                                                    @if ($settings->date_type=='shamsi')
                                                    {{$return->shamsi_date}}
                                                    @else
                                                    {{$return->miladi_date}}
                                                    @endif
                                                </td>
                                                <td>{{$return->sell_id}}</td>
                                                <td>{{$return->stock_product->product->name}}</td>
                                                <td>{{$return->stock_product->product->unit->name}}</td>
                                                <td>{{number_format($return->quantity)}}</td>
                                                <td>{{number_format($return->cost)}}</td>
                                                <td>{{$return->description}}</td>

                                            </tr>
                                            @php
                                            $total_quantity += $return->quantity;
                                            $total_cost += $return->cost ;


                                            @endphp
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th>{{ __('home.total') }}</th>
                                                <th></th>

                                                <th></th>

                                                <th></th>
                                                <th></th>
                                                <th>{{ $total_quantity }}</th>
                                                <th>{{ $total_cost }}</th>
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
@section('pagescript')
@endsection

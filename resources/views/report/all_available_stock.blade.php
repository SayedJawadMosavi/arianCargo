@extends('layouts.app')
@section('title', 'All Available Stock Report')

@section('content')

<div class="card mt-4">
    {{-- @dd(session()->has('success')) --}}
    @if (session()->has('success') || session()->has('error') )
    @include('layouts.partials.components.alert')
    @endif

    <div class="card-header ">
        <h3 class="card-title">{{ __('home.all_vailable_report') }}</h3>
    </div>
    <div class="card-body pt-4">
        <div class="col-sm-12 my-3">


        </div>
        <div class="table-responsive">
            <table id="file-datatable" class="table table-bordered table-striped text-nowrap mb-0 table-hover">
                <thead>
                <th>{{ __('home.sn') }}</th>

                    <th>{{ __('home.category') }}</th>
                    <th>{{ __('home.name') }}</th>
                    <th>{{ __('home.unit') }}</th>
                    <th>{{ __('home.main_stock_quantity') }}</th>
                    <th>{{ __('home.stock_quantity') }}</th>
                    <th>{{ __('home.total') }}</th>
                    <th>{{ __('home.cost') }}</th>
                    <th>{{ __('home.sell') }}</th>
                    <th>{{ __('home.dimensions') }}</th>
                    </tr>
                </thead>
                @php $gquantity =0; @endphp
                <tbody>
                    @isset($products)

                    @forelse($products as $obj)

                    @if($obj->quantity > 0)
                    <tr>
                        <td>{{$loop->iteration}}</td>
                        <td>{{$obj->category->name}}</td>
                        <td>{{$obj->name}}</td>
                        <td>{{$obj->unit->name}}</td>
                        <td>{{$obj->quantity}}</td>
                        <td>{{$obj->totalQuantity}}</td>
                        <td>{{$obj->totalQuantity +$obj->quantity}}</td>
                        <td>{{number_format($obj->cost, 2)}} </td>
                        <td>{{number_format($obj->sell, 2)}}</td>
                        <td>{{$obj->height}} * {{$obj->width}} * {{$obj->length}}</td>
                    </tr>
                    @endif
                    @php
                    $gquantity += $obj->quantity +$obj->totalQuantity;

                    @endphp
                    @empty
                    @endforelse
                    @endisset
                </tbody>

                <tfoot>
                    <tr>

                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th>{{ __('home.total') }}</th>
                        <th>{{ number_format($gquantity) }}</th>

                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    {{-- card-body --}}
</div>
@endsection

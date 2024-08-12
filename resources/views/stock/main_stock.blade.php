@extends('layouts.app')
@section('title', 'Main Stock Report')

@section('content')

<div class="card mt-4">
    {{-- @dd(session()->has('success')) --}}
    @if (session()->has('success') || session()->has('error') )
    @include('layouts.partials.components.alert')
    @endif

    <div class="card-header ">
        <h3 class="card-title">{{ __('home.main_stock') }}</h3>
    </div>
    <div class="card-body pt-4">

        <div class="table-responsive">
            <table id="file-datatable" class="table table-bordered table-striped text-nowrap mb-0 table-hover">
                <thead class="border-top">
                    <tr>
                        <th>{{ __('home.sn') }}</th>
                        <th>{{ __('home.category') }}</th>
                        <th>{{ __('home.name') }}</th>
                        <th>{{ __('home.unit') }}</th>
                        <th>{{ __('home.quantity') }}</th>
                        <th>{{ __('home.total') }}</th>
                        <th>{{ __('home.dimensions') }}</th>
                        <th>{{ __('home.action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @php $total = 0; $gtotal = 0; @endphp
                    @foreach($logs as $product)
                    <tr>
                        <td>{{$loop->iteration}}</td>
                        <td>{{$product->category->name ??   ''}}</td>
                        <td><a href="{{ route('product.stock', $product->id) }}">{{ $product->name }}</a></td>
                        <td>{{$product->unit->name}}</td>
                        <td>{{$qty = $product->subProducts->sum('available')}}</td>
                        <td>{{ number_format($product->grand_total, 2)}}</td>

                        <td>{{$product->height}} * {{$product->width}} * {{$product->length}}</td>
                        <td>
                            <div class="g-2 ">
                                <a class="btn text-success btn-sm" href="{{route('stock.show', $product)}}" data-bs-toggle="tooltip" data-bs-original-title="{{ __('home.detail') }}"><span class="fe fe-eye fs-16"></span></a>

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
                        <th></th>
                        <th></th>
                        <th>{{ __('home.total') }}</th>
                        <th>{{ number_format($total, 2) }}</th>
                        <th>{{ number_format($gtotal, 2) }}</th>
                        <th></th>
                        <th></th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    {{-- card-body --}}
</div>
@endsection

@extends('layouts.app')

@section('title', 'New sell')
@section('content')

<style>

    .footer-image {
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 100px; /* Adjust based on your image height */
        background: url('/images/footer.jpeg') no-repeat center bottom;
        background-size: contain;
    }
</style>

<div class="card mt-4"id="printable-content">
    <div class="card-body" style="padding-bottom: 150px">
        <div class="row">
            <div class="col-lg-12 mb-5">

            <img src="{{ isset($settings) ? asset($settings->first()->bill_header) : asset('images/header.jpg') }}" class="header-brand-img logo-3" alt="Sash logo" style="width: 100%; border-radius: 5px">

            </div>

        </div>

        <div class="table-responsive push">
            <table class="table table-bordered table-hover mb-0 text-nowrap">
                <tbody>
                    <tr>
                        <th class="text-center">{{ __('home.sn') }}</th>
                        <th class="text-center">{{ __('home.model') }}</th>
                        <th class="text-center">{{ __('home.product') }}</th>
                        <th class="text-end">{{ __('home.cbm') }}</th>
                        <th class="text-end">{{ __('home.weight') }}</th>
                        <th class="text-center">{{ __('home.quantity') }}</th>
                        <th class="text-end">{{ __('home.cost') }}</th>
                        <th class="text-end">{{ __('home.total') }}</th>
                    </tr>
                    @foreach ($sell->detail as $obj )
                    @php $c = 1; @endphp
                        <tr>
                            <td class="text-center">{{ $c++ }}</td>
                            <td class="text-center">{{ $obj->product->model }}</td>
                            <td class="text-center">{{ $obj->product->name }}</td>
                            @php
                                $cbm = ($obj->product->height/100) * ($obj->product->width/100) * ($obj->product->length/100)
                            @endphp
                            <td class="text-end">{{$cbm * $obj->quantity}}</td>
                            <td class="text-end">{{$obj->product->weight * $obj->quantity}}</td>
                            <td class="text-end">{{ $obj->quantity }}</td>
                            <td class="text-end">{{ number_format($obj->cost, 2) }}</td>
                            <td class="text-end">{{ number_format($obj->total, 2) }}</td>
                        </tr>
                    @endforeach

                    <tr>

                        <td colspan="3" class="fw-bold text-uppercase text-end">{{ __('home.total') }}</td>
                        <td class="fw-bold text-end h4">{{ number_format($sell->total_cbm, 2) }}</td>
                        <td colspan="3" class="fw-bold text-end h4"></td>
                        <td class="fw-bold text-end h4">{{ number_format($sell->total, 2) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
       
    </div>
    <div class="card-footer text-end d-print-none">
        <button type="button" class="btn btn-primary mb-1" onclick="printCardContent();"><i class="si si-wallet"></i> {{ __('home.print') }}</button>
        <!-- <button type="button" class="btn btn-secondary mb-1" onclick="javascript:window.print();"><i class="si si-paper-plane"></i> Send Invoice</button>
        <button type="button" class="btn btn-danger mb-1" onclick="javascript:window.print();"><i class="si si-printer"></i> Print Invoice</button> -->
    </div>
</div>

@endsection
@section('pagescript')
<script>
    function printCardContent() {
        // Trigger the print dialog
        window.print();
    }
</script>
@endsection
<style>
@media print {
    /* Hide any extra blank page */
    @page {
        size: auto;  /* auto is the initial value */
        margin: 0;  /* this affects the margin in the printer settings */
    }

    body {
        margin: 0;
    }

    /* Hide other elements during printing */
    body * {
        visibility: hidden;
    }

    #printable-content, #printable-content * {
        visibility: visible;
    }
}
</style>






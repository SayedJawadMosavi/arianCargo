@extends('layouts.app')

@section('content')

<div class="row mt-4">
    @if (session()->has('success') || session()->has('error') )
        @include('layouts.partials.components.alert')
    @endif
    <div class="col-lg-6 col-md-6 col-sm-12 col-xl-3">
        <div class="card overflow-hidden">
            <div class="card-body">
                <div class="d-flex">
                    <div class="mt-2">
                        <h6 class="">{{ __('home.daily_sell') }}</h6>
                        {{-- <h2 class="mb-0 number-font">0</h2> --}}
                    </div>

                </div>

            </div>
        </div>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-12 col-xl-3">
        <div class="card overflow-hidden">
            <div class="card-body">
                <div class="d-flex">
                    <div class="mt-2">
                        <h6 class="">{{ __('home.daily_purchase') }}</h6>
                        {{-- <h2 class="mb-0 number-font">0</h2> --}}
                    </div>

                </div>

            </div>
        </div>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-12 col-xl-3">
        <div class="card overflow-hidden">
            <div class="card-body">
                <div class="d-flex">
                    <div class="mt-2">
                    <h6 class="">{{ __('home.daily_cash_received') }}</h6>
                        <h2 class="mb-0 number-font">0</h2>
                    </div>

                </div>

            </div>
        </div>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-12 col-xl-3">
        <div class="card overflow-hidden">
            <div class="card-body">
                <div class="d-flex">
                    <div class="mt-2">
                    <h6 class="">{{ __('home.daily_expense') }}</h6>
                    <h2 class="mb-0 number-font">0</h2>
                    </div>

                </div>

            </div>
        </div>
    </div>
    {{-- @dd($settings) --}}
    @if($settings->currency_id==null && $settings->check==0)
    <a href="{{url('setting')}}" class="btn btn-primary">{{ __('home.base_currency_set') }}</a>
    @endif
    <canvas id="salesChart" width="400" height="400"></canvas>
</div>



@endsection

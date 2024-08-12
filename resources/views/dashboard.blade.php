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
                        {{-- <h2 class="mb-0 number-font">{{ number_format($daily_total_sell) }}</h2> --}}
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
                        {{-- <h2 class="mb-0 number-font">{{ number_format($daily_total_purchase) }}</h2> --}}
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
                        <h2 class="mb-0 number-font">{{ number_format($daily_total_cash_received) }}</h2>
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
                    <h2 class="mb-0 number-font">{{ number_format($daily_total_expense) }}</h2>
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


<script>
    var dailySales = {!! $dailySales !!};
    var dailyPurchases = {!! $dailyPurchases !!};

    // Prepare data for Chart.js
    var labels = dailySales.map(function(item) {
        return item.date;
    });

    var salesData = dailySales.map(function(item) {
        return item.total_sales;
    });

    var purchasesData = dailyPurchases.map(function(item) {
        return item.total_purchases;
    });

    // Colors array for multiple datasets
    var colors = [
        {
            backgroundColor: 'rgba(54, 162, 235, 0.2)',
            borderColor: 'rgba(54, 162, 235, 1)'
        },
        {
            backgroundColor: 'rgba(255, 99, 132, 0.2)',
            borderColor: 'rgba(255, 99, 132, 1)'
        },
        {
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            borderColor: 'rgba(75, 192, 192, 1)'
        },
        {
            backgroundColor: 'rgba(153, 102, 255, 0.2)',
            borderColor: 'rgba(153, 102, 255, 1)'
        },
        {
            backgroundColor: 'rgba(255, 159, 64, 0.2)',
            borderColor: 'rgba(255, 159, 64, 1)'
        }
    ];

    // Create Chart.js chart
    var ctx = document.getElementById('salesChart').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Daily Sales',
                    data: salesData,
                    backgroundColor: colors[0].backgroundColor,
                    borderColor: colors[0].borderColor,
                    borderWidth: 1
                },
                {
                    label: 'Daily Purchases',
                    data: purchasesData,
                    backgroundColor: colors[1].backgroundColor,
                    borderColor: colors[1].borderColor,
                    borderWidth: 1
                }
            ]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>

@endsection

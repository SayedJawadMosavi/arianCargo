@extends('layouts.app')
@section('title', ' Profit and Loss Report')

@section('content')

<div class="card mt-4">
    {{-- @dd(session()->has('success')) --}}
    @if (session()->has('success') || session()->has('error') )
    @include('layouts.partials.components.alert')
    @endif

    <div class="card-header ">
        <h3 class="card-title">{{ __('home.profit_lost_report') }}</h3>
        <br/>
    </div>
    <div class="card-body pt-4">
        <h4 class="card-title">{{ __('home.investment') }}: {{ number_format($investment) }}</h4>
        <!-- <div class="col-sm-12 my-3">
            <form action="{{ route('report.stock_transfer.post') }}" method="POST">
                {{-- <x-date-component :data="$categories"/> --}}
                @csrf
                @method('POST')
                <div class="form-row align-items-center my-5 offset-md-1">
                    @if ($settings->date_type=='shamsi')
                    <div class="col-xl-3">
                        <label for="validationServer01">{{ __('home.from_date') }}</label>
                        <input type="text" class="form-control form-control " name="from_shamsi" autocomplete="off" id="dates">

                        @error('date')
                        <div id="" class="invalid-feedback">{{$message}}</div>
                        @enderror
                    </div>
                    <div class="col-xl-3 ">
                        <label for="validationServer01">{{ __('home.to_date') }}</label>
                        <input type="text" class="form-control form-control " name="to_shamsi" autocomplete="off" id="dates1">

                        @error('date')
                        <div id="" class="invalid-feedback">{{$message}}</div>
                        @enderror
                    </div>
                    @else
                    <div class="col-xl-3 ">
                        <label for="validationServer01">{{ __('home.from_date') }}</label>
                        <input type="date" class="form-control " id="date" name="from_miladi" autocomplete="off" value="{{ date('Y-m-d') }}">
                        @error('date')
                        <div id="" class="invalid-feedback">{{$message}}</div>
                        @enderror
                    </div>
                    <div class="col-xl-3 ">
                        <label for="validationServer01">{{ __('home.to_date') }}</label>
                        <input type="date" class="form-control " id="date" name="to_miladi" autocomplete="off" value="{{ date('Y-m-d') }}">
                        @error('date')
                        <div id="" class="invalid-feedback">{{$message}}</div>
                        @enderror
                    </div>
                    @endif

                    <div class="col-6 col-sm-2 ">
                        <label class="" for="inlineFormInputGroup"> </label>
                        <button type="submit" class="btn btn btn-outline-primary" style="margin-top: 29px"> {{__('home.send')}}</button>
                    </div>
                </div>

            </form>

        </div> -->
   <div class="row">
    <div class="col-md-6">
        <div class="card h-100 border-primary">
            <div class="card-header bg-primary">
                <strong class="text-white">{{__('home.assets')}}</strong></span>
            </div>
            <div class="card-body d-flex flex-column">

                <ul class="list-group list-group-flush flex-grow-1">
                    <li class="list-group-item">{{__('home.account_available')}} : <span class="text-success">{{number_format($account_available)}}</span></li>
                    {{-- <li class="list-group-item">{{ __('home.client_receivable')}} : <span class="text-success">{{number_format($client_receivable)}}</span></li> --}}
                    <li class="list-group-item">{{ __('home.client_receivable')}} : <span class="text-success">{{number_format(abs($client_depositable))}}</span></li>
                    <li class="list-group-item">{{ __('home.main_stock')}} : <span class="text-success">{{number_format($main_stock_receivable)}}</span></li>
                    <li class="list-group-item">{{ __('home.other_stocks')}} : <span class="text-success">{{number_format($all_stock_receivable)}}</span></li>
                    <li class="list-group-item">{{ __('home.vendor_receivable')}} : <span class="text-success">{{number_format(abs($vendor_depositable))}}</span></li>
                    <li class="list-group-item">{{ __('home.assets')}} : <span class="text-success">{{number_format($total_asset)}}</span></li>
                    <li class="list-group-item">{{ __('home.unreceived_products')}} : <span class="text-success">{{number_format($unreceived)}}</span></li>
                </ul>
            </div>
            <div class="card-footer bg-primary">
                @php ($asset = $account_available + $total_asset + $main_stock_receivable + $all_stock_receivable + abs($vendor_depositable) + abs($client_depositable) + $unreceived); @endphp
                <strong class="text-white">{{__('home.total')}}:</strong> <span class="text-white">{{number_format($asset, 2)}}</span>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card h-100 border-danger">
            <div class="card-header bg-danger" style="background: #e82646 !important">
                <strong class="text-white">{{__('home.liability')}}</strong></span>
            </div>
            <div class="card-body d-flex flex-column">

                <ul class="list-group list-group-flush flex-grow-1">
                    <li class="list-group-item">{{ __('home.vendor_payable')}} : <span class="text-danger">{{number_format($vendor_receivable)}}</span></li>
                    <li class="list-group-item">{{ __('home.client_payable')}} : <span class="text-danger">{{number_format($client_receivable)}}</span></li>
                </ul>
            </div>
            <div class="card-footer bg-danger">
                @php $liability = $vendor_receivable + $client_receivable; @endphp
                <strong class="text-white">{{__('home.total')}}:</strong> <span class="text-white">{{ number_format($liability, 2)}}</span>
            </div>
        </div>
    </div>
</div>

<div class="row mt-3">
    <div class="col-md-12">
        <div class="card bg-success h-auto">
            <div class="card-body d-flex flex-column">
                @php $balance = $asset - $liability; @endphp
                <h5 class="card-title text-white">{{__('home.grand_total')}} ({{__('home.assets')}} -  {{__('home.liability')}}): {{number_format($balance, 2) }}</h5>
            </div>
        </div>
    </div>
</div>
<div class="row mt-3">
    <div class="col-md-12">
        <div class="card bg-info h-auto">
            <div class="card-body d-flex flex-column">
                @php $pl=0;
                $pl = $balance - $investment; @endphp
                <h4 class="card-title text-white"> {{__('home.profit_loss')}} =  {{__('home.grand_total')}} - {{__('home.investment')}}: <span class="fw-bold text-{{ $pl >= 0 ? 'success' : 'warning' }}">{{ number_format($pl, 2) }} </span></h4>
            </div>
        </div>
    </div>
</div>


    </div>
    {{-- card-body --}}
</div>
@endsection

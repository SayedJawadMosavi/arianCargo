@extends('layouts.app')
@section('title', 'Vendor Statement')

@section('content')

<div class="card mt-4">
    {{-- @dd(session()->has('success')) --}}
    @if (session()->has('success') || session()->has('error') )
    @include('layouts.partials.components.alert')
    @endif
    <div class="card-header ">
        <h3 class="card-title">{{ __('home.statement') }} - {{ $vendor->company }} </h3>
        <a href="{{ route('vendors.create') }}" class="btn btn-outline-primary mx-5">{{ __('home.new_vendor') }}</a>
    </div>

    <div class="card-body pt-4">
        <div class="col-sm-12 my-3">
            @php $data = 'hey'; @endphp
            <form class="" action="{{ route('statement.filter') }}" method="POST">
                <x-date-component :data="$data" />
                <input type="hidden" value="{{$vendor->id}}" name="vendor_id">
            </form>
        </div>
        <table class="table table-bordered">
            @foreach ($VendorCurrency as $cur )
            <tr>
                <th>{{$cur->currency->name}}</th>
                <td>{{$cur->amount}}</td>
            </tr>

            @endforeach
        </table>

        <div class="table-responsive">
            <table id="file-datatable" class="table table-bordered table-striped text-nowrap mb-0 table-hover">
                <thead>
                    <tr>
                        <th>{{ __('home.date') }}</th>
                        <th>{{ __('home.description') }}</th>
                        <th>{{ __('home.currency') }}</th>
                        <th>{{ __('home.paid') }}</th>
                        <th>{{ __('home.received') }}</th>
                        <th>{{ __('home.balance') }}</th>
                        {{-- <th>{{ __('home.transaction_type') }}</th> --}}

                    </tr>
                </thead>
                <tbody>
                    @php $total_deposit = 0; $total_witdtraw = 0; @endphp
                    @foreach($logs as $obj)
                    <tr>
                        <td>
                            @if ($settings->date_type=='shamsi')
                            {{$obj->shamsi_date}}
                            @else
                            {{$obj->miladi_date}}
                            @endif
                        </td>
                        <td>{{$obj->description}}</td>
                        <td>{{$obj->vendor_currency->currency->name}}</td>
                        {{-- <td>{{$obj->amount}}</td> --}}
                        <td>@if($obj->type == 'paid' ){{number_format($obj->amount, 2)}} @endif</td>
                        <td>@if($obj->type == 'received' ){{number_format($obj->amount, 2)}} @endif</td>
                        <td>{{number_format($obj->available, 2)}}</td>
                        {{-- <td>
                            @if($obj->type == 'deposit' )
                            <span class="tag tag-radius tag-round tag-primary">{{__('home.deposit') }}</span>
                        @else
                        <span class="tag tag-radius tag-round tag-red">{{__('home.withdraw') }}</span>
                        @endif
                        </td> --}}


                    </tr>

                    {{-- @php
                    foreach ($vendor->currency as $curr) {
                    if ($obj->type == 'paid') {
                    if ($obj->vendor_currency_id == $curr->id) {
                    $total_deposit += $obj->amount;
                    }
                    } else {
                    if ($obj->vendor_currency_id == $curr->id) {
                    $total_witdtraw += $obj->amount;
                    }
                    }
                    }
                    @endphp --}}
                    @php
                    if ($obj->type == 'paid') {
                        $total_deposit += $obj->amount;
                    } else {
                        $total_witdtraw += $obj->amount;
                    }
                    @endphp

                    @endforeach
                </tbody>
                <tfoot>
                    <tr>


                        <th></th>
                        <th>{{ __('home.total') }}</th>
                        <th></th>
                        <th>{{ $total_deposit }}</th>

                        <th>{{ $total_witdtraw }}</th>

                        <th> {{__('home.balance') }}: {{$total_deposit - $total_witdtraw}}</th>

                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    {{-- card-body --}}
</div>
@endsection

<script>
    function update($id) {
        // alert('hi');
        $('.update_' + $id).submit();
    }
</script>

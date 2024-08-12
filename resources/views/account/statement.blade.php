@extends('layouts.app')
@section('title', 'Account Statement')

@section('content')

<div class="card mt-4">
    {{-- @dd(session()->has('success')) --}}
    @if (session()->has('success') || session()->has('error') )
    @include('layouts.partials.components.alert')
    @endif

    <div class="card-header d-flex justify-content-between">
        <h3 class="card-title">{{ __('home.statement') }} - {{ $account->name }} - {{ $account->currency->name }}</h3>
        @can('account.create')
        <a href="{{ route('account.create') }}" class="btn btn-primary mx-5">{{ __('home.new_account') }}</a>
        @endcan
    </div>

    <div class="card-body pt-4">
        <div class="col-sm-12 my-3">
            @php $data = 'hey'; @endphp
            <form class="" action="{{ route('account.statement.filter', $account->id) }}" method="POST">
                {{-- <x-table-component :data="$myCollection" /> --}}
                <x-date-component :data="$data" />
            </form>

        </div>
        <div class="table-responsive">
            <table id="file-datatable" class="table table-bordered table-striped text-nowrap mb-0 table-hover">
                <thead>
                    <tr>
                        <th>{{ __('home.date') }}</th>
                        <th>{{ __('home.description') }}</th>
                        <th>{{ __('home.deposit') }}</th>
                        <th>{{ __('home.withdraw') }}</th>
                        <th>{{ __('home.balance') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @php $total_deposit = 0; $total_witdtraw = 0; @endphp
                    @foreach($logs as $obj)
                    <tr>
                        @if ($settings->date_type=='shamsi')
                        <td>{{$obj->shamsi_date}}</td>
                        @else
                        <td>{{$obj->miladi_date}}</td>
                        @endif
                        <td>{{$obj->description}}</td>
                        <td>@if($obj->type == 'deposit' ){{number_format($obj->amount, 2)}} @endif</td>
                        <td>@if($obj->type == 'withdraw' ){{number_format($obj->amount, 2)}} @endif</td>
                        <td>{{number_format($obj->balance, 2)}} </td>

                    </tr>
                    @php
                    if ($obj->type == 'deposit') {
                    $total_deposit += $obj->amount;
                    } else {
                    $total_witdtraw += $obj->amount;
                    }
                    @endphp
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th>{{ __('home.total') }}</th>
                        <th> {{ __('home.balance') }} :{{ number_format($total_deposit - $total_witdtraw) }}</th>
                        <th>{{ number_format($total_deposit) }}</th>

                        <th>{{ number_format($total_witdtraw) }}</th>
                        <th></th>
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

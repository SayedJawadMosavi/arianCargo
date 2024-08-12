@extends('layouts.app')
@section('title', 'All Transactions')

@section('content')

<div class="card mt-4">
    @if (session()->has('success') || session()->has('error') )
    @include('layouts.partials.components.alert')
    @endif
    <div class="card-header d-flex justify-content-between">
        <h3 class="card-title">{{ __('home.all_transaction') }}</h3>
        @can('client_transaction.create')
        <a href="{{ route('client_transaction.create') }}" class="btn btn-primary mx-5">{{ __('home.new_transaction') }}</a>
        @endcan
    </div>

    <div class="card-body pt-4">
        <div class="grid-margin">
            <div class="">
                <div class="panel panel-primary">
                    {{-- <div class="tab-menu-heading tab-menu-heading-boxed">
                        <div class="tabs-menu-boxed">
                            <!-- Tabs -->
                            <ul class="nav panel-tabs client_transaction-sale">
                                <li><a href="#tab1" class="active" data-bs-toggle="tab">{{ __('home.all_transaction') }}</a></li>
                                <li><a href="#tab2" data-bs-toggle="tab" class="text-dark">{{ __('home.trashed') }}</a></li>
                            </ul>
                        </div>
                    </div> --}}
                    <div class="panel-body tabs-menu-body border-0 pt-0">
                        <div class="tab-content">
                            <div class="tab-pane active" id="tab1">
                                <div class="table-responsive">
                                    <table id="file-datatable" class="table table-bordered table-striped text-nowrap mb-0 table-hover">
                                        <thead class="border-top">
                                            <tr>
                                                <th>{{ __('home.sn') }}</th>
                                                <th>{{ __('home.description') }}</th>
                                                <th>{{ __('home.account') }}</th>

                                                <th>{{ __('home.type') }}</th>
                                                <th>{{ __('home.amount') }}</th>
                                                <th>{{ __('home.date') }}</th>
                                                <th>{{ __('home.action') }}</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                        @php $total_deposit = 0; $total_withdraw = 0; @endphp

                                            @foreach($client_transactions as $client_transaction)
                                            <tr>

                                                <td>{{$loop->iteration}}</td>
                                                <td>{{$client_transaction->description}}</td>
                                                <td>{{$client_transaction->account->name}}-{{$client_transaction->account->currency->name}}</td>

                                                <td>@if($client_transaction->type == 'withdraw')
                                                    {{ __('home.withdraw') }}
                                                @else
                                                    {{ __('home.deposit') }}
                                                @endif</td>

                                                <td>{{number_format($client_transaction->amount)}}</td>
                                                <td>
                                                    @if ($settings->date_type=='shamsi')
                                                    {{$client_transaction->shamsi_date}}
                                                    @else
                                                    {{$client_transaction->miladi_date}}
                                                    @endif
                                                </td>

                                                <td class="d-flex">
                                                    <div class="">
                                                        @can('client_transaction.edit')
                                                        <a class="btn text-primary btn-sm" href="{{route('client_transaction.edit', $client_transaction)}}" data-bs-toggle="tooltip" data-bs-original-title="{{ __('home.edit') }}"><span class="fe fe-edit fs-16"></span></a>
                                                        @endcan
                                                        @can('client_transaction.delete')
                                                        <form action="{{route('client_transaction.destroy', $client_transaction)}}" method="POST" class="d-none">
                                                            @method('delete')
                                                            @csrf
                                                            <button type="submit" data-bs-toggle="tooltip" data-bs-original-title="{{ __('home.delete') }}" class="btn text-danger btn-sm"><span class="fe fe-trash-2 fs-16"></span></button>
                                                        </form>
                                                        @endcan
                                                    </div>
                                                </td>
                                            </tr>
                                            @php
                                            if($client_transaction->type == 'deposit'){
                                                $total_deposit += $client_transaction->amount;
                                            } else {
                                                $total_withdraw += $client_transaction->amount;
                                            }
                                        @endphp
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="">{{ __('home.total') }}</th>
                                            <th colspan="3">{{ __('home.deposit') }}: @foreach($deposit as $key =>$value){{ $key }}: {{ $value }} @endforeach</th>
                                            <th colspan="3">{{ __('home.withdraw') }}: @foreach($withdraw as $key =>$value){{ $key }}: {{ $value }} @endforeach</th>
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

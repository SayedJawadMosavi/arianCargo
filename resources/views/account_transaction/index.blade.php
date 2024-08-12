@extends('layouts.app')
@section('title', 'All Transactions')

@section('content')

<div class="card mt-4">
    @if (session()->has('success') || session()->has('error') )
    @include('layouts.partials.components.alert')
    @endif
    <div class="card-header d-flex justify-content-between">
        <h3 class="card-title">{{ __('home.all_transaction') }}</h3>
        @can('account_transaction.create')
        <a href="{{ route('account_transaction.create') }}" class="btn btn-primary mx-5">{{ __('home.new_transaction') }}</a>
        @endcan
    </div>

    <div class="card-body pt-4">
        <div class="grid-margin">
            <div class="">
                <div class="panel panel-primary">
                    <div class="tab-menu-heading tab-menu-heading-boxed">
                        <div class="tabs-menu-boxed">
                            <!-- Tabs -->
                            <ul class="nav panel-tabs account_transaction-sale">
                                <li><a href="#tab1" class="active" data-bs-toggle="tab">{{ __('home.all_transaction') }}</a></li>
                                <li><a href="#tab2" data-bs-toggle="tab" class="text-dark">{{ __('home.trashed') }}</a></li>
                            </ul>
                        </div>
                    </div>
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
                    @php $total_deposit = 0; $total_witdtraw = 0; @endphp

                                            @foreach($account_transactions as $account_transaction)
                                            <tr>

                                                <td>{{$loop->iteration}}</td>
                                                <td>{{$account_transaction->description}}</td>
                                                <td>{{$account_transaction->account->name}}</td>
                                                <td>
                                                    @if($account_transaction->type == 'withdraw')
                                                    {{ __('home.withdraw') }}
                                                @else
                                                    {{ __('home.deposit') }}
                                                @endif
                                                </td>

                                                <td>{{number_format($account_transaction->amount)}}</td>
                                                <td>
                                                    @if ($settings->date_type=='shamsi')
                                                    {{$account_transaction->shamsi_date}}
                                                    @else
                                                    {{$account_transaction->miladi_date}}
                                                    @endif
                                                </td>

                                                <td class="d-flex">
                                                    @if($account_transaction->action == 'direct')
                                                    @can('account_transaction.edit')
                                                    <a class="btn text-primary btn-sm" href="{{route('account_transaction.edit', $account_transaction)}}" data-bs-toggle="tooltip" data-bs-original-title="{{ __('home.edit') }}"><span class="fe fe-edit fs-16"></span></a>
                                                    @endcan
                                                    @can('account_transaction.delete')
                                                    <form action="{{route('account_transaction.destroy', $account_transaction)}}" method="POST" class="d-inline">
                                                        @method('delete')
                                                        @csrf
                                                        <button type="submit" data-bs-toggle="tooltip" data-bs-original-title="{{ __('home.delete') }}" class="btn text-danger btn-sm"><span class="fe fe-trash-2 fs-16"></span></button>
                                                    </form>
                                                    @endcan
                                                    @endif
                                                </td>
                                            </tr>

                                            @php
                                            if ($account_transaction->type == 'deposit') {
                                                $total_deposit += $account_transaction->amount;
                                            } else {
                                                $total_witdtraw += $account_transaction->amount;
                                            }
                                            @endphp

                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th>{{ __('home.total') }}</th>
                                                <th>{{ $total_deposit }}</th>
                                                <th>{{ $total_witdtraw }}</th>
                                                <th> {{ __('home.balance') }}: {{$total_deposit - $total_witdtraw}}</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane" id="tab2">
                                <div class="table-responsive">
                                    <table id="file-datatable" class="table table-bordered table-striped text-nowrap mb-0 table-hover">
                                        <thead class="border-top">
                                            <tr>
                                            <th>{{ __('home.no') }}</th>
                                                <th>{{ __('home.description') }}</th>
                                                <th>{{ __('home.type') }}</th>
                                                <th>{{ __('home.amount') }}</th>
                                                <th>{{ __('home.date') }}</th>
                                                <th>{{ __('home.action') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($trashed as $account_transaction)
                                            <tr class="border-bottom">
                                                <td>{{$loop->iteration}}</td>
                                                <td>{{$account_transaction->description}}</td>
                                                <td>
                                                    @if($account_transaction->type == 'withdraw')
                                                    {{ __('home.withdraw') }}
                                                @else
                                                    {{ __('home.deposit') }}
                                                @endif
                                                </td>

                                                <td>{{number_format($account_transaction->amount)}}</td>
                                                <td>
                                                    @if ($settings->date_type=='shamsi')
                                                    {{$account_transaction->shamsi_date}}
                                                    @else
                                                    {{$account_transaction->miladi_date}}
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="g-2 ">
                                                        @can('account_transaction.restore')

                                                        <form action="{{route('account_transaction.restore', $account_transaction->id)}}" method="POST" class="d-inline">
                                                            @method('POST')
                                                            @csrf
                                                            <button type="submit" data-bs-toggle="tooltip" data-bs-original-title="Restore" class="btn text-primary btn-sm"><span class="fe fe-repeat fs-14"></span></button>
                                                        </form>
                                                        @endcan
                                                    @can('account_transaction.forceDelete')

                                                        <form action="{{route('account_transaction.forceDelete', $account_transaction->id)}}" method="POST" class="d-inline">
                                                            @method('delete')
                                                            @csrf
                                                            <button type="submit" data-bs-toggle="tooltip" data-bs-original-title="Delete Permanently" class="btn text-danger btn-sm"><span class="fe fe-trash-2 fs-14"></span></button>
                                                        </form>
                                                        @endcan
                                                    </div>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
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

@extends('layouts.app')
@section('title', 'All Transactions')

@section('content')

<div class="card mt-4">
    @if (session()->has('success') || session()->has('error') )
    @include('layouts.partials.components.alert')
    @endif
    <div class="card-header d-flex justify-content-between">
        <h3 class="card-title">{{ __('home.all_transaction') }}</h3>
        @can('shareholder_transaction.create')
        <a href="{{ route('shareholder_transaction.create') }}" class="btn btn-primary mx-5">{{ __('home.new_transaction') }}</a>
        @endcan
    </div>

    <div class="card-body pt-4">
        <div class="grid-margin">
            <div class="">
                <div class="panel panel-primary">
                    {{-- <div class="tab-menu-heading tab-menu-heading-boxed">
                        <div class="tabs-menu-boxed">
                            <!-- Tabs -->
                            <ul class="nav panel-tabs shareholder_transaction-sale">
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
                                                <th>{{ __('home.shareholder') }} </th>
                                                <th>{{ __('home.account') }}</th>
                                                <th>{{ __('home.description') }}</th>
                                                <th>{{ __('home.type') }}</th>
                                                <th>{{ __('home.amount') }}</th>
                                                <th>{{ __('home.date') }}</th>
                                                {{-- <th>{{ __('home.action') }}</th> --}}
                                            </tr>
                                        </thead>

                                        <tbody>
                                            @foreach($shareholder_transactions as $shareholder_transaction)
                                            <tr>

                                                <td>{{$loop->iteration}}</td>
                                                <td>{{$shareholder_transaction->share_holder->name}} </td>
                                                <td>{{$shareholder_transaction->account->name}} ({{$shareholder_transaction->account->currency->name}})</td>
                                                <td>{{$shareholder_transaction->description}}</td>
                                                <td>@if($shareholder_transaction->type == 'withdraw')
                                                    {{ __('home.withdraw') }}
                                                @else
                                                    {{ __('home.deposit') }}
                                                @endif</td>

                                                <td>{{number_format($shareholder_transaction->amount)}}</td>
                                                <td>
                                                    @if ($settings->date_type=='shamsi')
                                                    {{$shareholder_transaction->shamsi_date}}
                                                    @else
                                                    {{$shareholder_transaction->miladi_date}}
                                                    @endif
                                                </td>

                                                {{-- <td class="d-flex">
                                                    <div class="">
                                                        @can('shareholder_transaction.edit')
                                                        <a class="btn text-primary btn-sm d-none" href="{{route('shareholder_transaction.edit', $shareholder_transaction)}}" data-bs-toggle="tooltip" data-bs-original-title="{{ __('home.edit') }}"><span class="fe fe-edit fs-16"></span></a>
                                                        @endcan
                                                        @can('shareholder_transaction.delete')
                                                        <form action="{{route('shareholder_transaction.destroy', $shareholder_transaction)}}" method="POST" class="d-none">
                                                            @method('delete')
                                                            @csrf
                                                            <button type="submit" data-bs-toggle="tooltip" data-bs-original-title="{{ __('home.delete') }}" class="btn text-danger btn-sm"><span class="fe fe-trash-2 fs-16"></span></button>
                                                        </form>
                                                        @endcan
                                                    </div>
                                                </td> --}}
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

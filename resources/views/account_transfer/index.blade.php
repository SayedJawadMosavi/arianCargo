@extends('layouts.app')
@section('title', 'All Transfers')

@section('content')

<div class="card mt-4">
    @if (session()->has('success') || session()->has('error') )
    @include('layouts.partials.components.alert')
    @endif
    <div class="card-header d-flex justify-content-between">
        <h3 class="card-title">{{ __('home.all_transfer') }}</h3>
        @can('account_transfer.create')
        <a href="{{ route('account_transfer.create') }}" class="btn btn-primary mx-5">{{ __('home.new_transfer') }}</a>
        @endcan
    </div>

    <div class="card-body pt-4">
        <div class="grid-margin">
            <div class="">
                <div class="panel panel-primary">
                    <div class="tab-menu-heading tab-menu-heading-boxed">
                        <div class="tabs-menu-boxed">
                            <!-- Tabs -->
                            <ul class="nav panel-tabs account_transfer-sale">
                                <li><a href="#tab1" class="active" data-bs-toggle="tab">{{ __('home.all_transfer') }}</a></li>

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
                                                <th>{{ __('home.date') }}</th>
                                                <th>{{ __('home.from_account') }}</th>
                                                <th>{{ __('home.to_account') }}</th>


                                                <th>{{ __('home.amount') }}</th>
                                                <th>{{ __('home.description') }}</th>

                                                {{-- <th>{{ __('home.action') }}</th> --}}
                                            </tr>
                                        </thead>

                                        <tbody>
                                            @foreach($account_transfers as $account_transfer)
                                            <tr>

                                                <td>{{$loop->iteration}}</td>
                                                <td>
                                                    @if ($settings->date_type=='shamsi')
                                                    {{$account_transfer->shamsi_date}}
                                                    @else
                                                    {{$account_transfer->miladi_date}}
                                                    @endif
                                                </td>
                                                <td>{{$account_transfer->from_account->name}}  -> {{$account_transfer->currency->name}}</td>
                                                <td>{{$account_transfer->to_account->name}} -> {{$account_transfer->currency->name}}</td>



                                                <td>{{number_format($account_transfer->amount)}}</td>
                                                <td>{{$account_transfer->description}}</td>

                                                {{-- <td class="d-flex">
                                                    <div class="">
                                                        @can('account_transfer.edit')
                                                        <a class="btn text-primary btn-sm" href="{{route('account_transfer.edit', $account_transfer)}}" data-bs-toggle="tooltip" data-bs-original-title="{{ __('home.edit') }}"><span class="fe fe-edit fs-16"></span></a>
                                                        @endcan
                                                        @can('account_transfer.delete')

                                                        <form action="{{route('account_transfer.destroy', $account_transfer)}}" method="POST" class="d-inline">
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

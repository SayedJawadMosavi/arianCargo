@extends('layouts.app')
@section('title', 'Vendor Transactions')

@section('content')

<div class="card mt-4">
    @if (session()->has('success') || session()->has('error') )
    @include('layouts.partials.components.alert')
    @endif
    <div class="card-header">
        <h3 class="card-title">{{ __('home.all_transaction') }}</h3>
        <a href="{{ route('vendor_transaction.create') }}" class="btn btn-outline-primary mx-5">{{ __('home.new_transaction') }}</a>
    </div>

    <div class="card-body pt-4">
        <div class="grid-margin">
            <div class="">
                <div class="panel panel-primary">
                    <div class="tab-menu-heading tab-menu-heading-boxed">
                        <div class="tabs-menu-boxed">
                            <!-- Tabs -->
                            <ul class="nav panel-tabs vendor_transaction-sale">
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
                                                <th>{{ __('home.no') }}</th>
                                                <th>{{ __('home.description') }}</th>
                                                {{-- <th>{{ __('home.account') }}</th> --}}
                                                <th>{{ __('home.vendor') }} </th>
                                                    <th>{{ __('home.account') }}</th>
                                                <th>{{ __('home.type') }}</th>
                                                <th>{{ __('home.amount') }}</th>
                                                <th>{{ __('home.date') }}</th>
                                                <th>{{ __('home.action') }}</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                        @php $total_deposit = 0; $total_witdtraw = 0; @endphp

                                            @foreach($vendor_transactions as $vendor_transaction)
                                            <tr>
                                                <td>{{$loop->iteration}}</td>
                                                <td>{{$vendor_transaction->description}}</td>
                                                {{-- <td>{{$vendor_transaction->account->name}} ->{{$vendor_transaction->account->currency->name}}</td> --}}
                                                <td>{{$vendor_transaction->vendor->company}}</td>
                                                <td>{{$vendor_transaction->account->name}} </td>
                                                <td>
                                                    @if($vendor_transaction->type == 'withdraw')
                                                        {{ __('home.withdraw') }}
                                                    @else
                                                        {{ __('home.deposit') }}
                                                    @endif
                                                </td>


                                                <td>{{number_format($vendor_transaction->amount)}}</td>
                                                <td>
                                                    @if ($settings->date_type=='shamsi')
                                                    {{$vendor_transaction->shamsi_date}}
                                                    @else
                                                    {{$vendor_transaction->miladi_date}}
                                                    @endif
                                                </td>

                                                <td class="d-flex">
                                                    <div class="">
                                                        <a class="btn text-primary btn-sm" href="{{route('vendor_transaction.edit', $vendor_transaction)}}" data-bs-toggle="tooltip" data-bs-original-title="{{ __('home.edit') }}"><span class="fe fe-edit fs-16"></span></a>

                                                        <form action="{{route('vendor_transaction.destroy', $vendor_transaction)}}" method="POST" class="d-none">
                                                            @method('delete')
                                                            @csrf
                                                            <button type="submit" data-bs-toggle="tooltip" data-bs-original-title="{{ __('home.delete') }}" class="btn text-danger btn-sm"><span class="fe fe-trash-2 fs-16"></span></button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                            @php
                                                if($vendor_transaction->type == 'deposit'){
                                                    $total_deposit += $vendor_transaction->amount;
                                                } else {
                                                    $total_witdtraw += $vendor_transaction->amount;
                                                }
                                            @endphp
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th></th>
                                                <th></th>
                                                <th>{{ __('home.total') }}</th>
                                                <th colspan="2">{{ __('home.deposit') }}: {{ $total_deposit }}</th>


                                                <th colspan="2">{{ __('home.withdraw') }}: {{ $total_witdtraw }}</th>

                                                <th> {{ __('home.balance') }}: {{$total_deposit - $total_witdtraw}}</th>
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

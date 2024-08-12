@extends('layouts.app')
@section('title', 'All purchases')

@section('content')

<div class="card mt-4">
    @if (session()->has('success') || session()->has('error') )
    @include('layouts.partials.components.alert')
    @endif
    <div class="card-header d-flex justify-content-between">
        <h3 class="card-title">{{ __('home.purchases') }}</h3>
        @can('purchase.create')
        <a href="{{ route('purchase.create') }}" class="btn btn-primary mx-5">{{ __('home.new_purchase') }}</a>
        @endcan
    </div>

    <div class="card-body pt-4">
        <div class="col-sm-12 my-3">
            @php $data = 'hey'; @endphp
            <form class="" action="{{ route('purchase.filter') }}" method="POST">
                <x-date-component :data="$data" />
            </form>
        </div>
        <div class="grid-margin">
            <div class="">
                <div class="panel panel-primary">
                    <div class="tab-menu-heading tab-menu-heading-boxed">
                        <div class="tabs-menu-boxed">
                            <!-- Tabs -->
                            <ul class="nav panel-tabs purchase-sale">
                                <li><a href="#tab1" class="active" data-bs-toggle="tab">{{ __('home.all_purchases') }}</a></li>
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
                                                <th>{{ __('home.date') }}</th>
                                                <th>{{ __('home.company') }}</th>
                                                <th>{{ __('home.total') }}</th>
                                                <th>{{ __('home.paid') }}</th>
                                                <th>{{ __('home.balance') }}</th>
                                                <th>{{ __('home.action') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php $c =1;
                                            $gtotal=0;
                                            $gpaid=0;
                                            $gbalance=0;
                                            @endphp

                                            @foreach($purchases as $purchase)
                                            <tr>
                                                <td>{{$loop->iteration}}</td>
                                                <td>
                                                    @if ($settings->date_type=='shamsi')
                                                    {{$purchase->shamsi_date}}
                                                    @else
                                                    {{$purchase->miladi_date}}
                                                    @endif
                                                </td>
                                                <td>{{$purchase->vendor->company}}</td>
                                                <td>{{number_format($purchase->total, 2)}}</td>
                                                <td>{{number_format($purchase->paid, 2)}}</td>
                                                <td>{{number_format($purchase->balance, 2)}}</td>
                                                <td>
                                                    <div class="g-2 ">
                                                        @can('purchase.create')
                                                        <a class="btn text-primary btn-sm" href="{{route('purchase.edit', $purchase)}}" data-bs-toggle="tooltip" data-bs-original-title="{{ __('home.edit') }}"><span class="fe fe-edit fs-16"></span></a>
                                                        @endcan
                                                        @can('purchase.details')
                                                        <a class="btn text-success btn-sm" href="{{route('purchase.detail.get', $purchase)}}" data-bs-toggle="tooltip" data-bs-original-title="{{ __('home.detail') }}"><span class="fe fe-eye fs-16"></span></a>
                                                        @endcan
                                                        <a class="btn text-success btn-sm" href="{{route('purchase.receive.get', $purchase)}}" data-bs-toggle="tooltip" data-bs-original-title="{{ __('home.received') }}"><span class="fe fe-menu fs-16"></span></a>
                                                        @if(!$purchase->receive->count())
                                                            @can('purchase.delete')
                                                            <button type="button" class="btn text-danger btn-sm" data-bs-toggle="modal" data-bs-target="#confirmationModal{{ $purchase->id }}">
                                                                <span class="fe fe-trash-2 fs-16"></span>
                                                            </button>
                                                            @endcan
                                                        @endif
                                                        <!-- Confirmation Modal -->

                                                    </div>
                                                </td>
                                            </tr>
                                            <div class="modal fade " id="confirmationModal{{ $purchase->id }}">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h6 class="modal-title">Confirmation</h6><button aria-label="Close" class="btn-close" data-bs-dismiss="modal"><span aria-hidden="true">&times;</span></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            Are you sure you want to delete this record?
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                            <form action="{{ route('purchase.destroy', $purchase) }}" method="POST" class="d-inline">
                                                                @method('delete')
                                                                @csrf
                                                                <button type="submit" class="btn btn-danger">Delete</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            @php
                                                $gtotal += $purchase->total;
                                                $gpaid += $purchase->paid;
                                                $gbalance += $purchase->balance;

                                            @endphp
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th>&nbsp;</th>
                                                <th>&nbsp;</th>
                                                <th colspan="">{{ __('home.total') }}</th>
                                                <th>{{ number_format($gtotal) }}</th>
                                                <th>{{ number_format($gpaid) }}</th>
                                                <th>{{ number_format($gbalance) }}</th>
                                                <th>&nbsp;</th>

                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane" id="tab2">
                                <div class="table-responsive">
                                    <table id="file-datatable" class="table table-bordered table-striped text-nowrap key-buttons mb-0 table-hover">
                                        <thead class="border-top">
                                            <tr>
                                                <th>{{ __('home.no') }}</th>
                                                <th>{{ __('home.company') }}</th>
                                                <th>{{ __('home.total') }}</th>
                                                <th>{{ __('home.paid') }}</th>
                                                <th>{{ __('home.balance') }}</th>
                                                <th>{{ __('home.action') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($trashed as $purchase)
                                            <tr class="border-bottom">
                                                <td>{{$purchase->id}}</td>
                                                <td>{{$purchase->vendor->company}}</td>
                                                <td>{{$purchase->total}}</td>
                                                <td>{{$purchase->paid}}</td>
                                                <td>{{$purchase->balance}}</td>
                                                <td>
                                                    <div class="g-2 ">

                                                        <form action="{{route('purchase.restore', $purchase)}}" method="POST" class="d-inline">
                                                            @method('POST')
                                                            @csrf
                                                            <button type="submit" data-bs-toggle="tooltip" data-bs-original-title="Restore" class="btn text-primary btn-sm"><span class="fe fe-repeat fs-14"></span></button>
                                                        </form>
                                                        <form action="{{route('purchase.forceDelete', $purchase)}}" method="POST" class="d-inline">
                                                            @method('delete')
                                                            @csrf
                                                            <button type="submit" data-bs-toggle="tooltip" data-bs-original-title="Delete Permanently" class="btn text-danger btn-sm"><span class="fe fe-trash-2 fs-14"></span></button>
                                                        </form>
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

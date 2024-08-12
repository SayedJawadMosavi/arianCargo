@extends('layouts.app')
@section('title', 'All sells')

@section('content')

<div class="card mt-4">
    @if (session()->has('success') || session()->has('error') )
        @include('layouts.partials.components.alert')
    @endif
    <div class="card-header d-flex justify-content-between">
        <h3 class="card-title">{{ __('home.sells') }}</h3>
        @can('sell.create')
        <a href="{{ route('sell.create') }}" class="btn btn-primary mx-5">{{ __('home.new_sell') }}</a>
        @endcan
    </div>

    <div class="card-body pt-4">
        <div class="col-sm-12 my-3">
            @php $data = 'hey'; @endphp
            <form class="" action="{{ route('sell.filter') }}" method="POST" >
                <x-date-component :data="$data"/>
            </form>

        </div>
        <div class="grid-margin">
            <div class="">
                <div class="panel panel-primary">
                    <div class="tab-menu-heading tab-menu-heading-boxed">
                        <div class="tabs-menu-boxed">
                            <!-- Tabs -->
                            <ul class="nav panel-tabs sell-sale">
                                <li><a href="#tab1" class="active" data-bs-toggle="tab">{{  __('home.all_sells') }}</a></li>
                                <li><a href="#tab2" data-bs-toggle="tab" class="text-dark">{{ __('home.trashed') }}</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="panel-body tabs-menu-body border-0 pt-0">
                        <div class="tab-content">
                            <div class="tab-pane active mt-4" id="tab1">
                                <div class="table-responsive">
                                    <table id="file-datatable" class="table table-bordered table-striped text-nowrap mb-0 table-hover">
                                        <thead class="border-top">
                                            <tr>
                                                <th>{{ __('home.sn') }}</th>
                                                <th>{{ __('home.date') }}</th>
                                                <th>{{ __('home.bill') }}</th>
                                                <th>{{ __('home.client') }}</th>
                                                <th>{{ __('home.total') }}</th>
                                                <th>{{ __('home.paid') }}</th>
                                                <th>{{ __('home.currency') }}</th>
                                                <th>{{ __('home.balance') }}</th>
                                                <th>{{ __('home.profit') }}</th>
                                                <th>{{ __('home.action') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $c =1;
                                                $gtotal=0;
                                                $gpaid=0;
                                                $gbalance=0;
                                                $gprofit=0;
                                            @endphp
                                            @foreach($sells as $sell)

                                            <tr>
                                                <td>{{$c++}}</td>
                                                <td>
                                                    @if ($settings->date_type=='shamsi')
                                                    {{$sell->shamsi_date}}
                                                    @else
                                                    {{$sell->miladi_date}}
                                                    @endif
                                                </td>
                                                <td>{{($sell->bill)}}</td>
                                                <td>{{$sell->client->type!='walkin' ? $sell->client->name : $sell->client_name}}</td>
                                                <td>{{number_format($sell->total)}}</td>
                                                <td>{{number_format($sell->paid)}}</td>
                                                <td>{{($sell->currency->name)}}</td>
                                                <td>{{number_format($sell->balance)}}</td>
                                                <td>{{($profit = $sell->detail->sum('profit'))}} </td>
                                                <td>
                                                    <div class="g-2 ">
                                                        @can('sell.edit')
                                                        <a class="btn text-primary btn-sm" href="{{route('sell.edit', $sell)}}" data-bs-toggle="tooltip" data-bs-original-title="{{ __('home.edit') }}"><span class="fe fe-edit fs-16"></span></a>
                                                        @endcan

                                                        <a class="btn text-info btn-sm" href="{{route('sell.bill', $sell)}}" data-bs-toggle="tooltip" data-bs-original-title="{{ __('home.bill') }}"><span class="fe fe-book fs-16"></span></a>


                                                        @can('sell.delete')
                                                        <a class="btn text-success btn-sm" href="{{route('sell.detail.get', $sell)}}" data-bs-toggle="tooltip" data-bs-original-title="{{ __('home.detail') }}"><span class="fe fe-eye fs-16"></span></a>
                                                        <form action="{{route('sell.destroy', $sell)}}" method="POST" class="d-none">
                                                            @method('delete')
                                                            @csrf
                                                            <button type="submit" data-bs-toggle="tooltip"  data-bs-original-title="{{ __('home.delete') }}" class="btn text-danger btn-sm"><span class="fe fe-trash-2 fs-16"></span></button>
                                                        </form>
                                                        @endcan

                                                    </div>
                                                </td>
                                            </tr>
                                            @php
                                                $gprofit += $profit;
                                                $gtotal += $sell->total;
                                                $gpaid += $sell->paid;
                                                $gbalance += $sell->balance;

                                            @endphp
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th>&nbsp;</th>
                                                <th>&nbsp;</th>
                                                <th>&nbsp;</th>
                                                <th colspan="">{{ __('home.total') }}</th>
                                                <th>{{ number_format($gtotal) }}</th>
                                                <th>{{ number_format($gpaid) }}</th>
                                                <th>{{ number_format($gbalance) }}</th>
                                                <th>&nbsp;</th>
                                                <th>{{ number_format($gprofit) }}</th>
                                                <th>&nbsp;</th>

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
                                            <th>{{ __('home.sn') }}</th>
                                                <th>{{ __('home.date') }}</th>
                                                <th>{{ __('home.client') }}</th>
                                                <th>{{ __('home.total') }}</th>
                                                <th>{{ __('home.paid') }}</th>
                                                <th>{{ __('home.balance') }}</th>
                                                <th>{{ __('home.profit') }}</th>
                                                <th>{{ __('home.action') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($trashed as $sell)
                                            <tr class="border-bottom">
                                            <td>{{$c++}}</td>
                                                <td>
                                                    @if ($settings->date_type=='shamsi')
                                                    {{$sell->shamsi_date}}
                                                    @else
                                                    {{$sell->miladi_date}}
                                                    @endif
                                                </td>
                                                <td>{{$sell->client->type!='walkin' ? $sell->client->name : $sell->client_name}}</td>
                                                <td>{{number_format($sell->total)}}</td>
                                                <td>{{number_format($sell->paid)}}</td>
                                                <td>{{number_format($sell->balance)}}</td>
                                                <td>{{number_format(profit($sell->id))}} </td>
                                                <td>
                                                    <div class="g-2 ">

                                                        <form action="{{route('sell.restore', $sell)}}" method="POST" class="d-inline">
                                                            @method('POST')
                                                            @csrf
                                                            <button type="submit" data-bs-toggle="tooltip"  data-bs-original-title="Restore" class="btn text-primary btn-sm"><span class="fe fe-repeat fs-14"></span></button>
                                                        </form>
                                                        <form action="{{route('sell.forceDelete', $sell)}}" method="POST" class="d-inline">
                                                            @method('delete')
                                                            @csrf
                                                            <button type="submit" data-bs-toggle="tooltip"  data-bs-original-title="Delete Permanently" class="btn text-danger btn-sm"><span class="fe fe-trash-2 fs-14"></span></button>
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

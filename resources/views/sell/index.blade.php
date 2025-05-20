@extends('layouts.app')
@section('title', 'All Cargo')

@section('content')

<div class="card mt-4">
    @if (session()->has('success') || session()->has('error') )
    @include('layouts.partials.components.alert')
    @endif
    <div class="card-header d-flex justify-content-between">
        <h3 class="card-title">{{ __('home.sells') }}</h3>
        @can('cargo.create')
        <a href="{{ route('cargo.create') }}" class="btn btn-primary mx-5">    <i class="fe fe-plus me-1"></i> {{ __('home.new_sell') }}</a>
        @endcan
    </div>

    <div class="card-body pt-4">
        <div class="col-sm-12 my-3">
            @php $data = 'hey'; @endphp
            <form class="" action="{{ route('cargo.filter') }}" method="POST">
                <x-date-component :data="$data" />
            </form>

        </div>
        <div class="grid-margin">
            <div class="">
                <div class="panel panel-primary">
                    <div class="tab-menu-heading tab-menu-heading-boxed">
                        <div class="tabs-menu-boxed">
                            <!-- Tabs -->
                            <ul class="nav panel-tabs sell-sale">
                                <li><a href="#tab1" class="active" data-bs-toggle="tab">{{ __('home.all_sells') }}</a></li>
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
                                                <th>{{ __('home.sn') }}</th>
                                                <th>{{ __('home.sender') }}</th>
                                                <!-- <th>{{ __('home.sender_tazkira') }}</th> -->
                                                <!-- <th>{{ __('home.receiver') }}</th> -->
                                                <th>{{ __('home.total') }}</th>
                                                <th>{{ __('home.paid') }}</th>
                                                <th>{{ __('home.currency') }}</th>
                                                <th>{{ __('home.balance') }}</th>

                                                <th>{{ __('home.action') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                            $c =1;
                                            $gtotal=0;
                                            $gpaid=0;
                                            $gbalance=0;

                                            @endphp
                                            @foreach($cargos as $cargo)

                                            <tr>
                                                <td>{{ $c++ }}</td>
                                                <td>{{ $settings->date_type == 'shamsi' ? $cargo->shamsi_date : $cargo->miladi_date }}</td>
                                                <td>{{ $cargo->bill }}</td>
                                                <td>{{ $cargo->number }}</td>
                                                <td>{{ $cargo->client->type != 'walkin' ? $cargo->client->name : $cargo->client_name }}</td>

                                                <td class="text-end text-dark fw-bold">{{ number_format($cargo->total) }}</td>
                                                <td class="text-end text-success fw-bold">{{ number_format($cargo->paid) }}</td>
                                                <td>{{ $cargo->currency->name }}</td>
                                                <td class="text-end fw-bold {{ $cargo->balance > 0 ? 'text-danger' : 'text-success' }}">
                                                    {{ number_format($cargo->balance) }}
                                                </td>

                                            <td>
    <div class="d-flex align-items-center flex-nowrap gap-1">

        <a class="btn btn-outline-info btn-sm rounded-0" href="{{ route('cargo.bill', $cargo) }}" data-bs-toggle="tooltip" data-bs-original-title="{{ __('home.bill') }}">
            <span class="fe fe-book fs-16"></span>
        </a>

        <a data-bs-effect="effect-sign" data-bs-toggle="modal" href="#payModal{{ $cargo->id }}" class="btn btn-outline-info btn-sm rounded-0">
            <i class="fe fe-plus"></i>
        </a>

        @can('cargo.edit')
        <a class="btn btn-outline-primary btn-sm rounded-0" href="{{ route('cargo.edit', $cargo) }}" data-bs-toggle="tooltip" data-bs-original-title="{{ __('home.edit') }}">
            <span class="fe fe-edit fs-16"></span>
        </a>
        @endcan

        <a class="btn btn-outline-success btn-sm rounded-0" href="{{ route('cargo.detail.get', $cargo) }}" data-bs-toggle="tooltip" data-bs-original-title="{{ __('home.detail') }}">
            <span class="fe fe-eye fs-16"></span>
        </a>

        @can('cargo.delete')
        <button type="button" class="btn btn-outline-danger btn-sm rounded-0" data-bs-toggle="modal" data-bs-target="#confirmationModal{{ $cargo->id }}">
            <span class="fe fe-trash-2 fs-16"></span>
        </button>
        <!-- modal code unchanged -->
        @endcan

        <a class="btn btn-outline-secondary btn-sm rounded-0" href="{{ route('cargo.show', $cargo->id) }}" data-bs-toggle="tooltip" data-bs-original-title="{{ __('home.payment') }}">
            <i class="fe fe-credit-card fs-16"></i>
        </a>
    </div>
</td>


                                            </tr>
                                            @php

                                            $gtotal += $cargo->total;
                                            $gpaid += $cargo->paid;
                                            $gbalance += $cargo->balance;

                                            @endphp
                                            <div class="modal fade" id="payModal{{$cargo->id}}" tabindex="-1" aria-labelledby="payModalLabel{{$cargo->id}}" aria-hidden="true">
                                                <div class="modal-dialog modal-lg">
                                                    <form action="{{ route('cargo.pay', $cargo->id) }}" method="POST">
                                                        @csrf
                                                        <div class="modal-content border-0 shadow-lg">
                                                            <div class="modal-header bg-primary text-white rounded-top">
                                                                <h5 class="modal-title fw-semibold" id="payModalLabel{{$cargo->id}}">
                                                                    💵 {{ __('home.bill') }} — {{ $cargo->bill }}
                                                                </h5>
                                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>

                                                            <div class="modal-body px-4">
                                                                <div class="row g-3 align-items-center text-center mb-4">

                                                                    <div class="col-md-4">
                                                                        <div class="bg-light rounded p-3 border">
                                                                            <div class="text-muted small">{{ __('home.total') }}</div>
                                                                            <div class="fs-4 fw-bold text-dark">{{ number_format($cargo->total) }}</div>
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-md-4">
                                                                        <div class="bg-light rounded p-3 border">
                                                                            <div class="text-muted small">{{ __('home.paid') }}</div>
                                                                            <div class="fs-4 fw-bold text-success">{{ number_format($cargo->paid) }}</div>
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-md-4">
                                                                        <div class="bg-light rounded p-3 border">
                                                                            <div class="text-muted small">{{ __('home.balance') }}</div>
                                                                            <div class="fs-4 fw-bold text-danger">{{ number_format($cargo->balance) }}</div>
                                                                        </div>
                                                                    </div>

                                                                </div>

                                                                <div class="row g-3">
                                                                    <div class="col-md-6">
                                                                        <label class="form-label fw-semibold">{{ __('home.date') }}</label>
                                                                        @if ($settings->date_type == 'shamsi')
                                                                        <input type="text"
                                                                            class="form-control @error('date') is-invalid @enderror"
                                                                            name="shamsi_date"
                                                                            id="dates"
                                                                            autocomplete="off"
                                                                            value="{{ $cargo->shamsi_date }}">
                                                                        @else
                                                                        <input type="date" style="padding: 11px !important;"
                                                                            class="form-control @error('date') is-invalid @enderror"
                                                                            name="miladi_date"
                                                                            value="{{ $cargo->miladi_date ?? date('Y-m-d') }}">
                                                                        @endif
                                                                        @error('date')
                                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                                        @enderror
                                                                    </div>

                                                                    <div class="col-md-6">
                                                                        <label for="pay_amount_{{ $cargo->id }}" class="form-label fw-semibold">{{ __('home.pay_now') }}</label>
                                                                        <input type="number"
                                                                            min="1"
                                                                            max="{{ $cargo->balance }}"
                                                                            class="form-control form-control-lg text-center"
                                                                            id="pay_amount_{{ $cargo->id }}"
                                                                            name="pay_amount"
                                                                            placeholder="0"
                                                                            required>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="modal-footer bg-light border-top-0 rounded-bottom">
                                                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                                                    {{ __('home.cancel') }}
                                                                </button>
                                                                <button type="submit" class="btn btn-success">
                                                                    <i class="fe fe-dollar-sign me-1"></i> {{ __('home.save') }}
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>

                                            @endforeach
                                        </tbody>
                                        <tfoot class="bg-light text-end fw-bold">
                                            <tr>
                                                <td colspan="5" class="text-start">{{ __('home.total') }}</td>
                                                <td class="text-dark">{{ number_format($gtotal) }}</td>
                                                <td class="text-success">{{ number_format($gpaid) }}</td>
                                                <td></td>
                                                <td class="{{ $gbalance > 0 ? 'text-danger' : 'text-success' }}">{{ number_format($gbalance) }}</td>
                                                <td></td>
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

                                                <th>{{ __('home.bill') }}</th>
                                                <th>{{ __('home.sn') }}</th>
                                                <th>{{ __('home.sender') }}</th>
                                                <!-- <th>{{ __('home.sender_tazkira') }}</th> -->
                                                <!-- <th>{{ __('home.receiver') }}</th> -->
                                                <th>{{ __('home.total') }}</th>
                                                <th>{{ __('home.paid') }}</th>
                                                <th>{{ __('home.currency') }}</th>
                                                <th>{{ __('home.balance') }}</th>

                                                <th>{{ __('home.action') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($trashed as $cargo)
                                            <tr class="border-bottom">
                                                <td>{{$c++}}</td>
                                                <td>
                                                    @if ($settings->date_type=='shamsi')
                                                    {{$cargo->shamsi_date}}
                                                    @else
                                                    {{$cargo->miladi_date}}
                                                    @endif
                                                </td>

                                                <td>{{($cargo->bill)}}</td>
                                                <td>{{($cargo->number)}}</td>
                                                <td>{{$cargo->client->type!='walkin' ? $cargo->client->name : $cargo->client_name}}</td>
                                                <!-- <td>{{($cargo->client->nid)}}</td> -->
                                                <!-- <td>{{$cargo->client->type!='walkin' ? $cargo->receiver->name : $cargo->client_name}}</td> -->


                                                <td>{{number_format($cargo->total)}}</td>
                                                <td>{{number_format($cargo->paid)}}</td>
                                                <td>{{($cargo->currency->name)}}</td>
                                                <td>{{number_format($cargo->balance)}}</td>
                                                <td>
                                                    <div class="g-2 ">

                                                        <form action="{{route('cargo.restore', $cargo)}}" method="POST" class="d-inline">
                                                            @method('POST')
                                                            @csrf
                                                            <button type="submit" data-bs-toggle="tooltip" data-bs-original-title="Restore" class="btn text-primary btn-sm"><span class="fe fe-repeat fs-14"></span></button>
                                                        </form>
                                                        <form action="{{route('cargo.forceDelete', $cargo)}}" method="POST" class="d-inline">
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

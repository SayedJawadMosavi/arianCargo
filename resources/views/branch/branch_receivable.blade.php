@extends('layouts.app')
@section('title', 'Branch Receivable')

@section('content')

<div class="card mt-4">
    {{-- @dd(session()->has('success')) --}}
    @if (session()->has('success') || session()->has('error') )
    @include('layouts.partials.components.alert')
    @endif

    <div class="card-header ">
        <h3 class="card-title">{{ __('home.cargo_report') }}</h3>
    </div>
    <div class="card-body pt-4">
        <div class="col-sm-12 my-3">
            <form action="{{ route('report.branch_receivable.post') }}" method="POST">
                {{-- <x-date-component :data="$categories"/> --}}
                @csrf
                @method('POST')
                <div class="form-row align-items-center my-5 offset-md-1">

                    <div class="col-md-3">
                        <label>{{ __('home.branch') }}</label>
                        <select class="form-select" name="branch_id">

                            @foreach($branches as $branch)
                            <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    @if ($settings->date_type=='shamsi')
                    <div class="col-xl-3">
                        <label for="validationServer01">{{ __('home.from_date') }}</label>
                        <input type="text" class="form-control form-control " name="from_shamsi" autocomplete="off" id="dates">

                        @error('date')
                        <div id="" class="invalid-feedback">{{$message}}</div>
                        @enderror
                    </div>
                    <div class="col-xl-3 ">
                        <label for="validationServer01">{{ __('home.to_date') }}</label>
                        <input type="text" class="form-control form-control " name="to_shamsi" autocomplete="off" id="dates1">

                        @error('date')
                        <div id="" class="invalid-feedback">{{$message}}</div>
                        @enderror
                    </div>
                    @else
                    <div class="col-xl-3 ">
                        <label for="validationServer01">{{ __('home.from_date') }}</label>
                        <input type="date" class="form-control " id="date" name="from_miladi" autocomplete="off" value="{{ date('Y-m-d') }}">
                        @error('date')
                        <div id="" class="invalid-feedback">{{$message}}</div>
                        @enderror
                    </div>
                    <div class="col-xl-3 ">
                        <label for="validationServer01">{{ __('home.to_date') }}</label>
                        <input type="date" class="form-control " id="date" name="to_miladi" autocomplete="off" value="{{ date('Y-m-d') }}">
                        @error('date')
                        <div id="" class="invalid-feedback">{{$message}}</div>
                        @enderror
                    </div>
                    @endif


                    <div class="col-6 col-sm-2 ">
                        <label class="" for="inlineFormInputGroup"> </label>
                        <button type="submit" class="btn btn btn-outline-primary" style="margin-top: 29px"> {{__('home.send')}}</button>
                    </div>
                </div>

            </form>

        </div>
        @isset($logs)
        @isset($accounts)
        @foreach($accounts as $account)
        <div class="card mb-4 border-0 shadow-sm">
            <div class="card-body py-3">
                <div class="d-flex flex-wrap align-items-center justify-content-between">
                    <div class="d-flex align-items-center mb-2 mb-md-0">
                        <div class="me-3">
                            <span class="badge bg-primary p-3 fs-5">
                                <i class="text-white fe fe-user"></i>
                            </span>
                        </div>
                        <div>
                            <div class="text-muted fe fe-user">{{ __('home.account') }}</div>
                            <div class="fs-5">{{ $account->name ?? '' }}</div>
                        </div>
                    </div>

                    <div class="text-center mx-3">
                        <div class="text-muted small">{{ __('home.total') }}</div>
                        <div class="fs-5 fw-bold text-primary">
                            {{ number_format($account->cargo_amount) }}
                        </div>
                    </div>

                    <div class="text-center mx-3">
                        <div class="text-muted small">{{ __('home.paid') }}</div>
                        <div class="fs-5 fw-bold text-success">
                            {{ number_format($account->paid_amount) }}
                        </div>
                    </div>

                    <div class="text-center mx-3">
                        <div class="text-muted small">{{ __('home.balance') }}</div>
                        <div class="fs-5 fw-bold text-danger">
                           {{ number_format($account->cargo_amount - $account->paid_amount) }}

                        </div>
                    </div>
                    <div class="text-center mx-3">
                        <form action="{{ route('account.pay') }}" method="POST" class="row g-2 align-items-center">
                            @csrf
                            <input type="hidden" name="account_id" value="{{ $account->id }}">

                            <div class="col-auto">
                                <input type="number" name="amount" class="form-control form-control-sm" placeholder="Amount" required>
                            </div>
                            <div class="col-auto">
                                @if ($settings->date_type=='shamsi')
                                <input type="text" class="form-control form-control @error('date') {{'is-invalid'}} @enderror" name="shamsi_date" autocomplete="off" id="dates" value="{{isset($cargo) ? $cargo->shamsi_date : old('date')}}">
                                @else
                                <input type="date" class="form-control @error('date') {{'is-invalid'}} @enderror" id="date" name="miladi_date" value="{{ isset($cargo) ? $cargo->miladi_date : date('Y-m-d') }}">

                                @endif
                            </div>
                            <div class="col-auto">
                                <input type="text" name="description" class="form-control form-control-sm" placeholder="Description (optional)">
                            </div>
                            <div class="col-auto">
                                <button type="submit" class="btn btn-sm btn-outline-success">پرداخت</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
        @endisset

        @endisset
        <div class="table-responsive">
            @php
            $gtotal = 0;
            $gpaid = 0;
            $gbalance = 0;
            $c = 1;
            @endphp

            <table id="file-datatable" class="table table-bordered table-striped text-nowrap mb-0 table-hover">
                <thead>
                    <tr>
                        <th>{{ __('home.sn') }}</th>
                        <th>{{ __('home.branch') }}</th>
                        <th>{{ __('home.date') }}</th>
                        <th>{{ __('home.bill') }}</th>
                        <th>{{ __('home.sn') }}</th>
                        <th>{{ __('home.sender') }}</th>
                        <th>{{ __('home.receiver') }}</th>
                        <th>{{ __('home.currency') }}</th>
                        <th>{{ __('home.total') }}</th>


                    </tr>
                </thead>

                <tbody>
                    @isset($logs)
                    @forelse($logs as $cargo)
                    <tr>
                        <td>{{ $c++ }}</td>
                        <td>{{ $cargo->branchs->name ?? '-' }}</td>

                        <td>{{ $settings->date_type == 'shamsi' ? $cargo->shamsi_date : $cargo->miladi_date }}</td>
                        <td>{{ $cargo->bill }}</td>
                        <td>{{ $cargo->number }}</td>
                        <td>{{ $cargo->client->type != 'walkin' ? $cargo->client->name : $cargo->client_name }}</td>
                        <td>{{ $cargo->receiver->type != 'walkin' ? $cargo->receiver->name : $cargo->client_name }}</td>
                        <td>{{ $cargo->currency->name }}</td>
                        <td class="text-end text-dark fw-bold">{{ number_format($cargo->total) }}</td>


                    </tr>
                    @php
                    $gtotal += $cargo->total;
                    $gpaid += $cargo->paid;
                    $gbalance += $cargo->balance;
                    @endphp
                    @empty
                    <tr>
                        <td colspan="9" class="text-center text-muted">{{ __('No data available') }}</td>
                    </tr>
                    @endforelse
                    @endisset
                </tbody>

                <tfoot class="bg-light text-end fw-bold">
                    <tr>
                        <td colspan="5" class="text-start">{{ __('home.total') }}</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td class="text-dark">{{ number_format($gtotal) }}</td>



                    </tr>
                </tfoot>
            </table>

        </div>
    </div>
    {{-- card-body --}}
</div>
@endsection

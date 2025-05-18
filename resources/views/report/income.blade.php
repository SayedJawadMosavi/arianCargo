@extends('layouts.app')
@section('title', 'Income Report')

@section('content')

<div class="card mt-4">
    {{-- @dd(session()->has('success')) --}}
    @if (session()->has('success') || session()->has('error') )
    @include('layouts.partials.components.alert')
    @endif

    <div class="card shadow-sm">
    <div class="card-header bg-primary text-white">
        <h4 class="mb-0">{{ __('home.income_report') }}</h4>
    </div>
    <div class="card-body pt-4">
        <form action="{{ route('report.income.post') }}" method="POST" class="row g-3 mb-4">
            @csrf
            @method('POST')

            @if(auth()->user()->hasRole('admin'))
                <div class="col-md-3">
                    <label>{{ __('home.branch') }}</label>
                    <select class="form-select" name="branch_id">
                        <option value="all">{{ __('home.all') }}</option>
                        @foreach($branches as $branch)
                            <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                        @endforeach
                    </select>
                </div>
            @endif

            @if ($settings->date_type == 'shamsi')
                <div class="col-md-2">
                    <label>{{ __('home.from_date') }}</label>
                    <input type="text" class="form-control" name="from_shamsi" autocomplete="off" id="dates">
                </div>
                <div class="col-md-2">
                    <label>{{ __('home.to_date') }}</label>
                    <input type="text" class="form-control" name="to_shamsi" autocomplete="off" id="dates1">
                </div>
            @else
                <div class="col-md-2">
                    <label>{{ __('home.from_date') }}</label>
                    <input type="date" class="form-control" name="from_miladi" value="{{ date('Y-m-d') }}">
                </div>
                <div class="col-md-2">
                    <label>{{ __('home.to_date') }}</label>
                    <input type="date" class="form-control" name="to_miladi" value="{{ date('Y-m-d') }}">
                </div>
            @endif

            <div class="col-md-2">
                <label>{{ __('home.category') }}</label>
                <select class="form-select" name="category_id">
                    <option value="all">{{ __('home.all') }}</option>
                    @foreach($categories as $obj)
                        <option value="{{ $obj->id }}">{{ $obj->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-1 d-flex align-items-end">
                <button type="submit" class="btn btn-outline-primary w-100">
                    {{ __('home.send') }}
                </button>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover table-borderless text-center align-middle"id="file-datatable">
                <thead class="table-light">
                    <tr>
                        <th>{{ __('home.date') }}</th>
                        <th>{{ __('home.branch') }}</th>
                        <th>{{ __('home.description') }}</th>
                        <th>{{ __('home.amount') }}</th>
                        <th>{{ __('home.currency') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @php $totals = []; @endphp

                    @isset($logs)
                        @forelse($logs as $obj)
                            <tr>
                                <td>{{ $settings->date_type == 'shamsi' ? $obj->shamsi_date : $obj->miladi_date }}</td>
                                <td>{{ $obj->branchs->name ?? '-' }}</td>
                                <td>{{ $obj->description }}</td>
                                <td>
                                    <span class="badge bg-success fs-6">
                                        {{ number_format($obj->amount, 2) }}
                                    </span>
                                </td>
                                <td>{{ $obj->account->currency->name ?? '-' }}</td>
                            </tr>

                            @php
                                $currency = $obj->account->currency->name ?? 'Unknown';
                                $totals[$currency] = ($totals[$currency] ?? 0) + $obj->amount;
                            @endphp
                        @empty
                            <tr>
                                <td colspan="5" class="text-danger fw-bold">{{ __('No data found') }}</td>
                            </tr>
                        @endforelse
                    @endisset
                </tbody>
                @if(!empty($totals))
                    <tfoot class="table-light fw-bold">
                        <tr>
                            <td colspan="3" class="text-end">{{ __('Total') }}:</td>
                            <td colspan="2">
                                @foreach($totals as $currency => $total)
                                    <span class="badge bg-warning fs-6 me-1">
                                        {{ number_format($total, 2) }} {{ $currency }}
                                    </span>
                                @endforeach
                            </td>
                        </tr>
                    </tfoot>
                @endif
            </table>
        </div>
    </div>
</div>

    {{-- card-body --}}
</div>
@endsection

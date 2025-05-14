@extends('layouts.app')
@section('title', 'Cargo Report')

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
            <form action="{{ route('report.cargo.post') }}" method="POST">
                {{-- <x-date-component :data="$categories"/> --}}
                @csrf
                @method('POST')
                <div class="form-row align-items-center my-5 offset-md-1">

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
                        <th>{{ __('home.date') }}</th>
                        <th>{{ __('home.bill') }}</th>
                        <th>{{ __('home.sn') }}</th>
                        <th>{{ __('home.sender') }}</th>
                        <th>{{ __('home.total') }}</th>
                        <th>{{ __('home.paid') }}</th>
                        <th>{{ __('home.currency') }}</th>
                        <th>{{ __('home.balance') }}</th>
                    </tr>
                </thead>

                <tbody>
                    @isset($logs)
                    @forelse($logs as $cargo)
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
                        <td class="text-dark">{{ number_format($gtotal) }}</td>
                        <td class="text-success">{{ number_format($gpaid) }}</td>
                        <td></td>
                        <td class="{{ $gbalance > 0 ? 'text-danger' : 'text-success' }}">{{ number_format($gbalance) }}</td>
                    </tr>
                </tfoot>
            </table>

        </div>
    </div>
    {{-- card-body --}}
</div>
@endsection

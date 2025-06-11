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
                        <!-- <th>{{ __('home.sender_tazkira') }}</th> -->
                        <th>{{ __('home.receiver') }}</th>
                        @if ($branch->is_main_branch==0)
                        <th>{{ __('home.branch_payable') }}</th>
                        @endif
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
                        <td>{{ $cargo->receiver->type != 'walkin' ? $cargo->receiver->name : $cargo->client_name }}</td>
                        @if ($settings->currency_id == $cargo->currency_id)
                        @if ($branch->is_main_branch == 0)
                        <td class="text-end">
                            <span class="badge bg-warning fs-6">
                                {{ number_format($cargo->total) }} $
                            </span>
                        </td>
                        @endif

                        <td class="text-end">
                            <span class="badge bg-primary fs-6">
                                {{ number_format($cargo->new_total) }}
                            </span>
                        </td>

                        <td class="text-end">
                            <span class="badge bg-success fs-6">
                                {{ number_format($cargo->paid) }}
                            </span>
                        </td>

                        <td>

                            {{ $cargo->currency->name }}

                        </td>

                        <td class="text-end">
                            <span class="badge {{ $cargo->balance > 0 ? 'bg-danger' : 'bg-success' }} fs-6">
                                {{ number_format($cargo->new_balance) }}
                            </span>
                        </td>

                        @else
                        @if ($branch->is_main_branch == 0)
                        <td class="text-end">
                            <span class="badge bg-warning fs-6">
                                {{ number_format($cargo->total) }} $
                            </span>
                        </td>
                        @endif

                        <td class="text-end">
                            <span class="badge bg-primary fs-6">
                                {{ number_format($cargo->equalent_total) }}
                            </span>
                        </td>

                        <td class="text-end">
                            <span class="badge bg-success fs-6">
                                {{ number_format($cargo->paid_equalent) }}
                            </span>
                        </td>

                        <td>

                            {{ $cargo->currency->name }}

                        </td>

                        <td class="text-end">
                            <span class="badge {{ $cargo->equalent_balance > 0 ? 'bg-danger' : 'bg-success' }} fs-6">
                                {{ number_format($cargo->equalent_balance) }}
                            </span>
                        </td>

                        @endif




                    </tr>

                 @php
    if ($settings->currency_id == $cargo->currency_id) {
        $gtotal += $cargo->new_total;
        $gpaid += $cargo->paid;
        $gbalance += $cargo->new_balance;
    } else {
        $gtotal += $cargo->equalent_total;
        $gpaid += $cargo->paid_equalent;
        $gbalance += $cargo->equalent_balance;
    }
@endphp

                    @empty
                    <tr>
                        <td colspan="9" class="text-center text-muted">{{ __('No data available') }}</td>
                    </tr>
                    @endforelse
                    @endisset
                </tbody>

                <tfoot>
                    <tr>
                        <th colspan="6" class="text-end">{{ __('Total') }}</th>
                        @if ($branch->is_main_branch == 0)
                        <th></th> {{-- total --}}
                        @endif
                        <th class="text-end text-primary">{{ number_format($gtotal) }}</th>
                        <th class="text-end text-success">{{ number_format($gpaid) }}</th>
                        <th></th> {{-- currency --}}
                        <th class="text-end {{ $gbalance > 0 ? 'text-danger' : 'text-success' }}">{{ number_format($gbalance) }}</th>
                    </tr>
                </tfoot>

            </table>

        </div>
    </div>
    {{-- card-body --}}
</div>
@endsection

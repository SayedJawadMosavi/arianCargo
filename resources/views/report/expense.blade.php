@extends('layouts.app')
@section('title', 'Expense Report')

@section('content')

<div class="card mt-4">
    {{-- @dd(session()->has('success')) --}}
    @if (session()->has('success') || session()->has('error') )
    @include('layouts.partials.components.alert')
    @endif

    <div class="card-header ">
        <h3 class="card-title">{{ __('home.expense_report') }}</h3>
    </div>
    <div class="card-body pt-4">
        <div class="col-sm-12 my-3">
            <form action="{{ route('report.expense.post') }}" method="POST">
                {{-- <x-date-component :data="$categories"/> --}}
                @csrf
                @method('POST')

                <div class="form-row align-items-center my-5 offset-md-1">
                    @if(auth()->user()->hasRole('admin')==true)
                    <div class="col-6 col-sm-3">
                        <label class="" for="inlineFormInputGroup"> {{__('home.branch')}}</label>
                        <select class="form-control " name="branch" id="branch">
                            <option value="all">{{__('home.all')}}</option>
                            @foreach($branches as $branch)
                            <option value="{{$branch->id}}" @if (isset($user)) @if($user->branch_id == $branch->id)
                                selected="selected" @endif @endif>{{$branch->name}}</option>
                            @endforeach

                        </select>
                        @error('from')
                        <span class="alert text-danger">{{$message}}</span>
                        @enderror
                    </div>
                    @endif
                    @if ($settings->date_type=='shamsi')
                    <div class="col-xl-2">
                        <label for="validationServer01">{{ __('home.from_date') }}</label>
                        <input type="text" class="form-control form-control " name="from_shamsi" autocomplete="off" id="dates">

                        @error('date')
                        <div id="" class="invalid-feedback">{{$message}}</div>
                        @enderror
                    </div>
                    <div class="col-xl-2 ">
                        <label for="validationServer01">{{ __('home.to_date') }}</label>
                        <input type="text" class="form-control form-control " name="to_shamsi" autocomplete="off" id="dates1">

                        @error('date')
                        <div id="" class="invalid-feedback">{{$message}}</div>
                        @enderror
                    </div>
                    @else
                    <div class="col-xl-2 ">
                        <label for="validationServer01">{{ __('home.from_date') }}</label>
                        <input type="date" class="form-control " id="date" name="from_miladi" autocomplete="off" value="{{ date('Y-m-d') }}">
                        @error('date')
                        <div id="" class="invalid-feedback">{{$message}}</div>
                        @enderror
                    </div>
                    <div class="col-xl-2 ">
                        <label for="validationServer01">{{ __('home.to_date') }}</label>
                        <input type="date" class="form-control " id="date" name="to_miladi" autocomplete="off" value="{{ date('Y-m-d') }}">
                        @error('date')
                        <div id="" class="invalid-feedback">{{$message}}</div>
                        @enderror
                    </div>
                    @endif

                    <div class="col-xl-2">
                        <label for="validationServer04">{{ __('home.category') }}</label>
                        <select class="form-selects form-control" id="validationServer04" aria-describedby="validationServer04Feedback" required name="category_id">
                            <option value="all">{{ __('home.all') }}</option>
                            @foreach($categories as $obj)
                            <option value="{{$obj->id}}"> {{ $obj->name }}</option>
                            @endforeach
                        </select>

                    </div>

                    <div class="col-6 col-sm-2 ">
                        <label class="" for="inlineFormInputGroup"> </label>
                        <button type="submit" class="btn btn btn-outline-primary" style="margin-top: 29px"> {{__('home.send')}}</button>
                    </div>
                </div>

            </form>

        </div>
        <div class="card border-0 shadow-sm mt-4">
    <div class="card-header bg-primary text-white fw-bold text-center">
        {{ __('Expense Report') }}
    </div>
    <div class="card-body p-0">
        <div class="table-responsive mt-5">
            <table class="table table-hover table-borderless text-nowrap mt-5" id="file-datatable">
                <thead class="bg-light text-center text-secondary">
                    <tr>
                        <th>{{ __('home.date') }}</th>
                        <th>{{ __('home.branch') }}</th>
                        <th>{{ __('home.description') }}</th>
                        <th>{{ __('home.amount') }}</th>
                        <th>{{ __('home.currency') }}</th>
                    </tr>
                </thead>
                <tbody class="text-center align-middle">
                    @php $totals = []; @endphp

                    @isset($logs)
                        @forelse($logs as $obj)
                            <tr>
                                <td>
                                    {{ $settings->date_type == 'shamsi' ? $obj->shamsi_date : $obj->miladi_date }}
                                </td>
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
                                $currencyName = $obj->account->currency->name ?? 'Unknown';
                                $totals[$currencyName] = ($totals[$currencyName] ?? 0) + $obj->amount;
                            @endphp
                        @empty
                            <tr>
                                <td colspan="5" class="text-danger text-center fw-bold">
                                    {{ __('No data found') }}
                                </td>
                            </tr>
                        @endforelse
                    @endisset
                </tbody>

                @if (!empty($totals))
                <tfoot class="bg-light text-center fw-semibold">
                    <tr>
                        <td colspan="3" class="text-end">{{ __('Total') }}:</td>
                        <td colspan="2">
                            @foreach($totals as $currency => $total)
                                <span class="badge bg-dark me-1 fs-6">
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

    </div>
    {{-- card-body --}}
</div>
@endsection

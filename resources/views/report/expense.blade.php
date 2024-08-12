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

                    @if ($settings->date_type=='shamsi')
                        <div class="col-xl-3">
                            <label for="validationServer01">{{ __('home.from_date') }}</label>
                            <input type="text" class="form-control form-control " name="from_shamsi" autocomplete="off" id="dates" >

                            @error('date')
                            <div id="" class="invalid-feedback">{{$message}}</div>
                            @enderror
                        </div>
                        <div class="col-xl-3 ">
                            <label for="validationServer01">{{ __('home.to_date') }}</label>
                            <input type="text" class="form-control form-control " name="to_shamsi" autocomplete="off" id="dates1" >

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

                    <div class="col-xl-3">
                        <label for="validationServer04">{{ __('home.category') }}</label>
                        <select class="form-selects form-control" id="validationServer04" aria-describedby="validationServer04Feedback" required name="category_id">
                            <option value="all">{{ __('home.all') }}</option>
                            @foreach($categories as $obj)
                                <option value="{{$obj->id}}" > {{ $obj->name }}</option>
                            @endforeach
                        </select>

                    </div>

                    <div class="col-6 col-sm-2 ">
                        <label class="" for="inlineFormInputGroup" >   </label>
                        <button type="submit" class="btn btn btn-outline-primary" style="margin-top: 29px">  {{__('home.send')}}</button>
                    </div>
                </div>

            </form>

        </div>
        <div class="table-responsive">
            <table id="file-datatable" class="table table-bordered table-striped text-nowrap mb-0 table-hover">
                <thead>
                    <tr>
                        <th>{{ __('home.date') }}</th>
                        <th>{{ __('home.description') }}</th>
                        <th>{{ __('home.amount') }}</th>
                        <th>{{ __('home.currency') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @isset($logs)
                        @forelse($logs as $obj)
                            <tr>
                                @if ($settings->date_type=='shamsi')
                                    <td>{{$obj->shamsi_date}}</td>
                                @else
                                    <td>{{$obj->miladi_date}}</td>
                                @endif
                                <td>{{$obj->description}}</td>
                                <td>{{number_format($obj->amount, 2)}} </td>
                                <td>{{$obj->account->currency->name}}</td>
                            </tr>
                        @empty
                        @endforelse
                    @endisset
                </tbody>
            </table>
        </div>
    </div>
    {{-- card-body --}}
</div>
@endsection

@extends('layouts.app')

@section('title', 'New Transaction')
@section('content')

<div class="card mt-4">
    <div class="card-header d-flex justify-content-between">
        <h4 class="card-title">
            @if(isset($AccountTransaction))
            {{ __('home.edit_transaction') }}
            @else
            {{ __('home.new_transaction') }}
            @endif
        </h4>
        @if (session()->has('success') || session()->has('error') )
        @include('layouts.partials.components.alert')
        @endif
        <a href="{{route('account_transaction.index')}}" class="btn btn-primary">{{ __('home.all_transaction') }}</a>

    </div>
    <div class="card-body ">
        <form action="{{isset($AccountTransaction) ? route('account_transaction.update', $AccountTransaction) : route('account_transaction.store')}}" method="POST" enctype="multipart/form-data">
            @csrf
            @if(isset($AccountTransaction))
            @method('PUT')
            @else
            @method('POST')
            @endif

            <div class="form-row mb-3">


                <div class="col-xl-3 mb-3">
                    <label for="validationServer04">{{ __('home.account') }}</label>
                    <select class="form-selects form-control @error('account') {{'is-invalid'}} @enderror" id="validationServer04" aria-describedby="validationServer04Feedback" required name="account_id">
                        @foreach($accounts as $account)
                        <option value="{{$account->id}}" @if(isset($AccountTransaction)) @if($AccountTransaction->account_id == $account->id) selected = 'selected' @endif @endif> {{ $account->name }} - {{ $account->currency->name }} - {{ $account->amount }}</option>
                        @endforeach
                    </select>
                    @error('account_id')
                    <div id="" class="invalid-feedback">{{$message}}</div>
                    @enderror
                </div>
                <div class="col-12 col-sm-4 col-md-3 col-md-2">
                    <label class="" for="inlineFormInputGroup">{{__('home.type')}}</label>
                    <select class="form-control " name="type" id="type" onchange="showData(this.value);">
                        <option value="deposit" @if(isset($AccountTransaction)) @if($AccountTransaction->type == 'deposit') selected = 'selected' @endif @endif >{{__('home.deposit')}}</option>
                        <option value="withdraw" @if(isset($AccountTransaction)) @if($AccountTransaction->type == 'withdraw') selected = 'selected' @endif @endif >{{__('home.withdraw')}}</option>
                    </select>
                    @error('type')
                    <span class="alert text-danger">{{$message}}</span>
                    @enderror
                </div>

                <div class="col-xl-3 mb-3">
                    <label for="validationServer01">{{ __('home.amount') }}</label>
                    <input type="number" step="0.01" class="form-control @error('amount') {{'is-invalid'}} @enderror" id="amount" name="amount" value="{{isset($AccountTransaction) ? $AccountTransaction->amount : old('amount')}}">
                    @error('amount')
                    <div id="" class="invalid-feedback">{{$message}}</div>
                    @enderror
                </div>
                @if ($settings->date_type=='shamsi')
                <div class="col-xl-3 mb-3">
                    <label for="validationServer01">{{ __('home.date') }}</label>
                    <input type="text" class="form-control form-control @error('date') {{'is-invalid'}} @enderror" name="shamsi_date" autocomplete="off" id="dates" value="{{isset($AccountTransaction) ? $AccountTransaction->date : old('date')}}">

                    @error('date')
                    <div id="" class="invalid-feedback">{{$message}}</div>
                    @enderror
                </div>
                @else
                <div class="col-xl-3 mb-3">
                    <label for="validationServer01">{{ __('home.date') }}</label>
                    <input type="date" class="form-control @error('date') {{'is-invalid'}} @enderror" id="date" name="miladi_date" value="{{ isset($AccountTransaction) ? $AccountTransaction->miladi_date : date('Y-m-d') }}">
                    @error('date')
                    <div id="" class="invalid-feedback">{{$message}}</div>
                    @enderror
                </div>
                @endif
                <div class="col-12 col-sm-12">
                    <label class="" for="inlineFormInputGroup">{{__('home.description')}}</label>
                    <textarea name="description" class="form-control" id="description">{{isset($AccountTransaction) ? $AccountTransaction->description: old('description')}}</textarea>

                    @error('address')
                    <span class="alert text-danger">{{$message}}</span>
                    @enderror
                </div>
            </div>

            <div class="form-footer mt-2">
                <input type="submit" class="btn btn-primary" value="@if(isset($AccountTransaction))
                    {{ __('home.update') }}
                @else
                    {{ __('home.save') }}
                @endif">
            </div>

        </form>

    </div>
</div>

@endsection
@section('pagescript')
<script>
    $('#dates').persianDatepicker({
        // minDate: new persianDate().subtract('day', 1).valueOf(),
        maxDate: new persianDate(),
        format: 'YYYY-MM-DD',
        autoClose: true,
        initialValue: true,
        initialValueType: 'persian',
        calendar: {
            persian: {
                locale: 'en'
            }
        }
    });
</script>
<script>
    function showData(value) {


        $.ajax({
            url: "{{URL::asset('/')}}" + "select_data/" + value,
            method: 'GET',
            success: function(data) {
                $('.form-select').html(data.data);

            }
        });

    }
</script>
@endsection

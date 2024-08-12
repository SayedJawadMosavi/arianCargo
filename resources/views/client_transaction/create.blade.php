@extends('layouts.app')

@section('title', 'New Transaction')
@section('content')

<div class="card mt-4">
    <div class="card-header d-flex justify-content-between">
        <h4 class="card-title">
            @if(isset($clientTransaction))
            {{ __('home.edit_transaction') }}
            @else
            {{ __('home.new_transaction') }}
            @endif
        </h4>
        @if (session()->has('success') || session()->has('error') )
        @include('layouts.partials.components.alert')
        @endif
        <a href="{{route('client_transaction.index')}}" class="btn btn-primary">{{ __('home.all_transaction') }}</a>

    </div>
    <div class="card-body ">
        <form action="{{isset($clientTransaction) ? route('client_transaction.update', $clientTransaction) : route('client_transaction.store')}}" method="POST" enctype="multipart/form-data">
            @csrf
            @if(isset($clientTransaction))
            @method('PUT')
            @else
            @method('POST')
            @endif

            <div class="form-row mb-3">
            <div class="col-xl-3 mb-3">

            <label for="client_id">{{ __('home.client') }}</label>
                    <select class="form-selects form-control select2 @error('client') {{'is-invalid'}} @enderror" id="client_id" aria-describedby="validationServer04Feedback" required name="client_id" onchange="showData(this.value);">
                        <option selected disabled value="">Choose...</option>
                        @foreach($clients as $client)
                            <option value="{{$client->id}}" @if(isset($clientTransaction)) @if($clientTransaction->client_id == $client->id) selected = 'selected' @endif @endif> {{ $client->name }}</option>
                        @endforeach
                    </select>
                    @error('client_id')
                    <div id="" class="invalid-feedback">{{$message}}</div>
                    @enderror
                </div>
                <div class="col-xl-3 mb-3">
                    <label for="validationServer04">{{ __('home.account') }}</label>
                    <select class="form-selects form-control @error('account') {{'is-invalid'}} @enderror" id="account_id" aria-describedby="validationServer04Feedback" required name="account_id" >
                    <option selected disabled value="">Choose...</option>
                    @foreach($accounts as $account)
                        <option value="{{$account->id}}" @if(isset($clientTransaction)) @if($clientTransaction->account_id == $account->id) selected = 'selected' @endif @endif data-currency="{{ $account->currency->name }}"> {{ $account->name }} - {{ $account->currency->name }} - {{ $account->amount }}</option>
                        @endforeach
                    </select>
                    @error('account_id')
                    <div id="" class="invalid-feedback">{{$message}}</div>
                    @enderror
                </div>


                <div class="col-12 col-sm-4 col-md-3 col-md-2">
                    <label class="" for="inlineFormInputGroup">{{__('home.type')}}</label>
                    <select class="form-control " name="type" id="type" >
                        <option value="deposit" @if(isset($clientTransaction)) @if($clientTransaction->type == 'deposit') selected = 'selected' @endif @endif >{{__('home.deposit')}}</option>
                        <option value="withdraw" @if(isset($clientTransaction)) @if($clientTransaction->type == 'withdraw') selected = 'selected' @endif @endif >{{__('home.withdraw')}}</option>
                    </select>
                    @error('type')
                    <span class="alert text-danger">{{$message}}</span>
                    @enderror
                </div>

                <div class="col-xl-3 mb-3">
                    <label for="validationServer01" class="AmountLabel">{{ __('home.amount') }}</label>
                    <input type="number" step="0.01" class="form-control @error('amount') {{'is-invalid'}} @enderror" id="amount" name="amount" value="{{isset($clientTransaction) ? $clientTransaction->amount : old('amount', 0)}}">
                    @error('amount')
                    <div id="" class="invalid-feedback">{{$message}}</div>
                    @enderror
                </div>

                @if ($settings->date_type=='shamsi')
                <div class="col-xl-3 mb-3">
                    <label for="validationServer01">{{ __('home.date') }}</label>
                    <input type="text" class="form-control form-control @error('date') {{'is-invalid'}} @enderror" name="shamsi_date" autocomplete="off" id="dates" value="{{isset($clientTransaction) ? $clientTransaction->shamsi_date : old('shamsi_date')}}">

                    @error('date')
                    <div id="" class="invalid-feedback">{{$message}}</div>
                    @enderror
                </div>
                @else
                <div class="col-xl-3 mb-3">
                    <label for="validationServer01">{{ __('home.date') }}</label>
                    <input type="date" class="form-control @error('date') {{'is-invalid'}} @enderror" id="date" name="miladi_date" value="{{ isset($clientTransaction) ? $clientTransaction->miladi_date : date('Y-m-d') }}">
                    @error('date')
                    <div id="" class="invalid-feedback">{{$message}}</div>
                    @enderror
                </div>
                @endif
                <div class="col-12 col-sm-12">
                    <label class="" for="inlineFormInputGroup">{{__('home.description')}}</label>
                    <textarea name="description" class="form-control" id="description">{{isset($clientTransaction) ? $clientTransaction->description: old('description')}}</textarea>

                    @error('description')
                    <span class="alert text-danger">{{$message}}</span>
                    @enderror
                </div>
            </div>

            <div class="form-footer mt-2">
                <input type="submit" class="btn btn-primary" value="@if(isset($clientTransaction))
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
    function checkCurrency(value) {

        var account_id = $('#account_id').val();

        $.ajax({
            url: "{{URL::asset('/')}}" + "findCurrency/" + account_id + '/' + value,
            method: 'GET',
            success: function(data) {
                if (data.account_id != data.currency_id) {
                    $('#operations').css('display', 'block')
                    $('#exchange_rates').css('display', 'block')
                    $('#totals').css('display', 'block')
                } else {
                    $('#operations').css('display', 'none')
                    $('#exchange_rates').css('display', 'none')
                    $('#totals').css('display', 'none')
                }

            }
        });

    }

    function exchange_data(value) {

        var amount = parseFloat($('#amount').val());
        var exchange_rate = parseFloat($('#exchange_rate').val());
        var total;

        if (value === "multiply") {
            total = amount * exchange_rate;

        } else {
            total = amount / exchange_rate;
        }

        $('#total').val(total.toFixed(2)); // Assuming you want to round the result to 2 decimal places
    }
</script>
<script>
    function showData(value) {

        $.ajax({
            url: '/get-client-data/' + value,
            type: 'GET',
            success: function(response) {
                var data = response.data;
                console.log('dd', data)

                var options = "<option value=''>Select Account</option>";
                data.forEach(function(account) {
                    options += "<option value='" + account.id + "'>" + account.name + " (" + account.amount + '-' + account.currency.name + ")</option>";

                });
                $('#account_id').html(options);
            },
            error: function(xhr, status, error) {
                console.error(error);

            }
        });
        $.ajax({
            url: "{{URL::asset('/')}}" + "getClientCurrency/" + value,
            method: 'GET',

            success: function(data) {
                $("#client_account_name").val(data.data.currency.name);
                $("#client_account_id").val(data.data.currency_id);


            }


        });

    }


    @isset($clientTransaction)
    $.ajax({
        type: 'POST',
        url: "{{URL::asset('/')}}" + "getClientCurrency/" + {!! $clientTransaction->client_id !!},
        method: 'GET',

        success: function(response) {
            $("#client_account_name").val(data.data.currency.name);
                $("#client_account_id").val(data.data.currency_id);

        }
    });

    $.ajax({
        url: "{{URL::asset('/')}}" + "findCurrency/" + {!! $clientTransaction->account_id !!} + '/' + {!! $clientTransaction->client_currency_id !!},
        method: 'GET',
        success: function(data) {
            if (data.account_id != data.currency_id) {
                $('#operations').css('display', 'block')
                $('#exchange_rates').css('display', 'block')
                $('#totals').css('display', 'block')
            } else {
                $('#operations').css('display', 'none')
                $('#exchange_rates').css('display', 'none')
                $('#totals').css('display', 'none')
            }

        }
    });



@endisset

// function CurrencyLabel(value){
//         // var currency = parseFloat($(this).find(':selected').data('currency'));
//         console.log(value);
//         // var currency = value.data('currency');
//         $('.AmountLabel').text(currency);
// }
</script>
@endsection

@extends('layouts.app')

@section('title', 'New Transaction')
@section('content')

<div class="card mt-4">
    <div class="card-header d-flex justify-content-between">
        @if (session()->has('success') || session()->has('error') )
            @include('layouts.partials.components.alert')
        @endif
        <h4 class="card-title">
            @if(isset($shareholderTransaction))
            {{ __('home.edit_transaction') }}
            @else
            {{ __('home.new_transaction') }}
            @endif
        </h4>
        <a href="{{route('shareholder_transaction.index')}}" class="btn btn-primary">{{ __('home.all_transaction') }}</a>

    </div>
    <div class="card-body ">
        <form action="{{isset($shareholderTransaction) ? route('shareholder_transaction.update', $shareholderTransaction) : route('shareholder_transaction.store')}}" method="POST" enctype="multipart/form-data">
            @csrf
            @if(isset($shareholderTransaction))
            @method('PUT')
            @else
            @method('POST')
            @endif

            <div class="form-row mb-3">


                <div class="col-xl-3 mb-3">
                    <label for="validationServer04">{{ __('home.account') }}</label>
                    <select class="form-selects form-control @error('account') {{'is-invalid'}} @enderror" id="account_id" aria-describedby="validationServer04Feedback" required name="account_id" >
                    <option selected disabled value="">Choose...</option>
                    @foreach($accounts as $account)
                        <option value="{{$account->id}}" @if(isset($shareholderTransaction)) @if($shareholderTransaction->account_id == $account->id) selected = 'selected' @endif @endif data-currency="{{ $account->currency->name }}"> {{ $account->name }} - {{ $account->currency->name }} - {{ $account->amount }}</option>
                        @endforeach
                    </select>
                    @error('account_id')
                    <div id="" class="invalid-feedback">{{$message}}</div>
                    @enderror
                </div>
                <div class="col-xl-3 mb-3">
                    <label for="shareholder_id">{{ __('home.shareholder') }}</label>
                    <select class="form-selects form-control select2 @error('shareholder') {{'is-invalid'}} @enderror" id="shareholder_id" aria-describedby="validationServer04Feedback" required name="shareholder" onchange="showData(this.value);">
                        <option selected disabled value="">Choose...</option>
                        @foreach($shareholders as $shareholder)
                            <option value="{{$shareholder->id}}" @if(isset($shareholderTransaction)) @if($shareholderTransaction->share_holder_id == $shareholder->id) selected = 'selected' @endif @endif> {{ $shareholder->name }}</option>
                        @endforeach
                    </select>
                    @error('shareholder_id')
                    <div id="" class="invalid-feedback">{{$message}}</div>
                    @enderror
                </div>
                {{-- <div class="col-xl-3 mb-3">

                    <label class="" for="inlineFormInputGroup">{{__('home.accounts')}}</label>

                    <select class="form-control " name="shareholder_account_id" id="shareholder_account_id" onchange="checkCurrency(this.value);">
                        <option selected disabled value="">Choose...</option>
                        @foreach($currencies as $currency)
                        <option value="{{$currency->id}}" @if(isset($shareholderTransaction)) @if($shareholderTransaction->share_holder_id == $currency->id) selected = 'selected' @endif @endif> {{ $currency->name }}</option>
                    @endforeach
                    </select>

                </div> --}}
                <div class="col-12 col-sm-4 col-md-3 col-md-2">
                    <label class="" for="inlineFormInputGroup">{{__('home.type')}}</label>
                    <select class="form-control " name="type" id="type" >
                        <option value="deposit" @if(isset($shareholderTransaction)) @if($shareholderTransaction->type == 'deposit') selected = 'selected' @endif @endif >{{__('home.deposit')}}</option>
                        <option value="withdraw" @if(isset($shareholderTransaction)) @if($shareholderTransaction->type == 'withdraw') selected = 'selected' @endif @endif >{{__('home.withdraw')}}</option>
                    </select>
                    @error('type')
                    <span class="alert text-danger">{{$message}}</span>
                    @enderror
                </div>

                <div class="col-xl-3 mb-3">
                    <label for="validationServer01" class="AmountLabel">{{ __('home.amount') }}</label>
                    <input type="number" step="0.01" class="form-control @error('amount') {{'is-invalid'}} @enderror" id="amount" name="amount" value="{{isset($shareholderTransaction) ? $shareholderTransaction->amount : old('amount')}}">
                    @error('amount')
                    <div id="" class="invalid-feedback">{{$message}}</div>
                    @enderror
                </div>
                {{-- <div class="col-xl-3 mb-3" style="display: none;" id="exchange_rates">
                    <label class="label_rate_from" for="inlineFormInputGroup">
                        {{__('home.exchange_rate')}}</label>
                    <input type="text" class="form-control " name="exchange_rate" id="exchange_rate" value="{{isset($shareholderTransaction) ? $shareholderTransaction->rate : old('rate')}}">


                </div>
                <div class="col-12 col-sm-4 col-md-3 col-md-2" style="display: none;" id="operations">
                    <label class="" for="inlineFormInputGroup">{{__('home.operation')}}</label>
                    <select class="form-control " name="operation" id="operation" onchange="exchange_data(this.value);">
                    <option selected disabled value="">Choose...</option>
                    <option value="multiply" @if(isset($shareholderTransaction)) @if($shareholderTransaction->operation == 'multiply') selected = 'selected' @endif @endif >{{__('home.multiply')}}</option>
                        <option value="divide" @if(isset($shareholderTransaction)) @if($shareholderTransaction->operation == 'divide') selected = 'selected' @endif @endif >{{__('home.divide')}}</option>
                    </select>

                </div>
                <div class="col-xl-3 mb-3" style="display: none;" id="totals">
                    <label class="" for="inlineFormInputGroup"> {{__('home.total')}}</label>
                    <input type="number" step="0.01" class="form-control " name="total" id="total" value="{{isset($shareholderTransaction) ? $shareholderTransaction->total : old('total')}}">

                </div> --}}
                @if ($settings->date_type=='shamsi')
                <div class="col-xl-3 mb-3">
                    <label for="validationServer01">{{ __('home.date') }}</label>
                    <input type="text" class="form-control form-control @error('date') {{'is-invalid'}} @enderror" name="shamsi_date" autocomplete="off" id="dates" value="{{isset($shareholderTransaction) ? $shareholderTransaction->date : old('date')}}">

                    @error('date')
                    <div id="" class="invalid-feedback">{{$message}}</div>
                    @enderror
                </div>
                @else
                <div class="col-xl-3 mb-3">
                    <label for="validationServer01">{{ __('home.date') }}</label>
                    <input type="date" class="form-control @error('date') {{'is-invalid'}} @enderror" id="date" name="miladi_date" value="{{ isset($shareholderTransaction) ? $shareholderTransaction->miladi_date : date('Y-m-d') }}">
                    @error('date')
                    <div id="" class="invalid-feedback">{{$message}}</div>
                    @enderror
                </div>
                @endif
                <div class="col-12 col-sm-12">
                    <label class="" for="inlineFormInputGroup">{{__('home.description')}}</label>
                    <textarea name="description" class="form-control" id="description">{{isset($shareholderTransaction) ? $shareholderTransaction->description: old('description')}}</textarea>

                    @error('address')
                    <span class="alert text-danger">{{$message}}</span>
                    @enderror
                </div>
            </div>

            <div class="form-footer mt-2">
                <input type="submit" class="btn btn-primary" value="@if(isset($shareholderTransaction))
                    {{ __('home.update') }}
                @else
                    {{ __('home.save') }}
                @endif">
            </div>

        </form>

        <table class="table table-sm mt-2 table-bordered" id="datatables" style="display: none;">
            <thead>
                <tr>
                    <th scope="col">{{__('home.currency')}} </th>
                    <th scope="col"> {{__('home.amount')}}</th>
                </tr>
            </thead>
            <tbody>

            </tbody>
        </table>


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
            url: "{{URL::asset('/')}}" + "getshareholderCurrencyJson/" + value,
            type: 'GET',
            success: function(response) {
                var data = response.data;
                $('#datatables tbody').empty();
                if (!data || data.length === 0) {
                    $('#datatables').hide();
                    return;
                }
                data.forEach(function(product) {
                    // console.log('hey',data);
                    var amount = parseFloat(product.amount);
                    var rowColorClass = amount <= 0 ? 'table-danger' : 'table-success'; // Conditionally set color class
                    var newRow = "<tr class='" + rowColorClass + "'>" +
                        "<td>" + product.currency.name + "</td>" +
                        "<td>" + product.amount + "</td>" +
                        "</tr>";
                    $('#datatables tbody').append(newRow);
                });
                $('#datatables').show();
            },
            error: function(xhr, status, error) {
                console.error(error);

            }
        });

    }


    @isset($shareholderTransaction)
    $.ajax({
        type: 'POST',
        url: "{{URL::asset('/')}}" + "getshareholderCurrency/" + {!! $shareholderTransaction->share_holder_id !!},
        method: 'GET',

        success: function(response) {
            $("#shareholder_account_id").empty();
            $("#shareholder_account_id").append("<option value=''>حساب را انتخاب نمایید</option>");
            $('#shareholder_account_id').html(response.html);

            $("#shareholder_account_id option").each(function() {
                // console.log('Text:-' + this.text + '  Value:-' + this.value);
                if($(this).val() == {!! $shareholderTransaction->shareholder_currency_id !!}){
                    $(this).attr("selected","selected");
                }
            });

        }
    });

    $.ajax({
        url: "{{URL::asset('/')}}" + "findCurrency/" + {!! $shareholderTransaction->account_id !!} + '/' + {!! $shareholderTransaction->shareholder_currency_id !!},
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

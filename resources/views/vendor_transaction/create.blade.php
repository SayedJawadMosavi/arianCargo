@extends('layouts.app')

@section('title', 'New Transaction')
@section('content')

<div class="card mt-4">
    @if (session()->has('success') || session()->has('error') )
        @include('layouts.partials.components.alert')
    @endif

    <div class="card-header d-flex justify-content-between">
        <h4 class="card-title">
            @if(isset($vendorTransaction))
            {{ __('home.edit_transaction') }}
            @else
            {{ __('home.new_transaction') }}
            @endif
        </h4>
        @if (session()->has('success') || session()->has('error') )
        @include('layouts.partials.components.alert')
        @endif
        <a href="{{route('vendor_transaction.index')}}" class="btn btn-primary">{{ __('home.all_transaction') }}</a>

    </div>
    <div class="card-body ">
        <form action="{{isset($vendorTransaction) ? route('vendor_transaction.update', $vendorTransaction) : route('vendor_transaction.store')}}" method="POST" enctype="multipart/form-data">
            @csrf
            @if(isset($vendorTransaction))
            @method('PUT')
            @else
            @method('POST')
            @endif

            <div class="form-row mb-3">


                <div class="col-xl-3 mb-3">
                    <label for="validationServer04">{{ __('home.account') }}</label>
                    <select class="form-selects form-control @error('account') {{'is-invalid'}} @enderror" id="account_id" aria-describedby="validationServer04Feedback" required name="account_id">
                        <option selected disabled value="">Choose...</option>
                        @foreach($accounts as $account)
                        <option value="{{$account->id}}" @if(isset($vendorTransaction)) @if($vendorTransaction->account_id == $account->id) selected = 'selected' @endif @endif> {{ $account->name }} - {{ $account->currency->name }} - {{ $account->amount }}</option>
                        @endforeach
                    </select>
                    @error('account_id')
                    <div id="" class="invalid-feedback">{{$message}}</div>
                    @enderror
                </div>
                <div class="col-xl-3 mb-3">
                    <label for="vendor_id">{{ __('home.vendor') }}</label>
                    <select class="form-selects form-control select2 @error('vendor') {{'is-invalid'}} @enderror" id="vendor_id" aria-describedby="validationServer04Feedback" required name="vendor_id">
                        <option selected disabled value="">Choose...</option>
                        @foreach($vendors as $vendor)
                        <option value="{{$vendor->id}}" @if(isset($vendorTransaction)) @if($vendorTransaction->vendor_id == $vendor->id) selected = 'selected' @endif @endif> {{ $vendor->company }} - {{ $vendor->contact_person }}</option>
                        @endforeach
                    </select>
                    @error('vendor_id')
                    <div id="" class="invalid-feedback">{{$message}}</div>
                    @enderror
                </div>

                <div class="col-12 col-sm-4 col-md-3 col-md-2">
                    <label class="" for="inlineFormInputGroup">{{__('home.type')}}</label>
                    <select class="form-control " name="type" id="type">
                        <option selected disabled value="">Choose...</option>
                        <option value="deposit" @if(isset($vendorTransaction)) @if($vendorTransaction->type == 'deposit') selected = 'selected' @endif @endif >{{__('home.deposit')}}</option>
                        <option value="withdraw" @if(isset($vendorTransaction)) @if($vendorTransaction->type == 'withdraw') selected = 'selected' @endif @endif >{{__('home.withdraw')}}</option>
                    </select>
                    @error('type')
                    <span class="alert text-danger">{{$message}}</span>
                    @enderror
                </div>

                <div class="col-xl-3 mb-3">
                    <label for="validationServer01">{{ __('home.amount') }}</label>
                    <input type="number" step="0.01" class="form-control @error('amount') {{'is-invalid'}} @enderror" id="amount" name="amount" value="{{isset($vendorTransaction) ? $vendorTransaction->amount : old('amount')}}">
                    @error('amount')
                    <div id="" class="invalid-feedback">{{$message}}</div>
                    @enderror
                </div>
                <div class="col-xl-3 mb-3" style="display: none;" id="exchange_rates">
                    <label class="label_rate_from" for="inlineFormInputGroup">
                        {{__('home.exchange_rate')}}</label>
                    <input type="text" class="form-control " name="exchange_rate" id="exchange_rate" value="{{isset($vendorTransaction) ? $vendorTransaction->rate : old('rate')}}">


                </div>
                <div class="col-12 col-sm-4 col-md-3 col-md-2" style="display: none;" id="operations">
                    <label class="" for="inlineFormInputGroup">{{__('home.operation')}}</label>
                    <select class="form-control " name="operation" id="operation" onchange="exchange_data(this.value);">
                        <option selected disabled value="">Choose...</option>
                        <option value="multiply" @if(isset($vendorTransaction)) @if($vendorTransaction->operation == 'multiply') selected = 'selected' @endif @endif >{{__('home.multiply')}}</option>
                        <option value="divide" @if(isset($vendorTransaction)) @if($vendorTransaction->operation == 'divide') selected = 'selected' @endif @endif >{{__('home.divide')}}</option>
                    </select>

                </div>
                <div class="col-xl-3 mb-3" style="display: none;" id="totals">
                    <label class="" for="inlineFormInputGroup"> {{__('home.total')}}</label>
                    <input type="number" step="0.01" class="form-control " name="total" id="total" value="{{isset($vendorTransaction) ? $vendorTransaction->total : old('total')}}">

                </div>
                @if ($settings->date_type=='shamsi')
                <div class="col-xl-3 mb-3">
                    <label for="validationServer01">{{ __('home.date') }}</label>
                    <input type="text" class="form-control form-control @error('date') {{'is-invalid'}} @enderror" name="shamsi_date" autocomplete="off" id="dates" value="{{isset($vendorTransaction) ? $vendorTransaction->date : old('date')}}">

                    @error('date')
                    <div id="" class="invalid-feedback">{{$message}}</div>
                    @enderror
                </div>
                @else
                <div class="col-xl-3 mb-3">
                    <label for="validationServer01">{{ __('home.date') }}</label>
                    <input type="date" class="form-control @error('date') {{'is-invalid'}} @enderror" id="date" name="miladi_date" value="{{ isset($vendorTransaction) ? $vendorTransaction->miladi_date : date('Y-m-d') }}">
                    @error('date')
                    <div id="" class="invalid-feedback">{{$message}}</div>
                    @enderror
                </div>
                @endif
                <div class="col-12 col-sm-12">
                    <label class="" for="inlineFormInputGroup">{{__('home.description')}}</label>
                    <textarea name="description" class="form-control" id="description">{{isset($vendorTransaction) ? $vendorTransaction->description: old('description')}}</textarea>

                    @error('address')
                    <span class="alert text-danger">{{$message}}</span>
                    @enderror
                </div>
            </div>

            <div class="form-footer mt-2">
                <input type="submit" class="btn btn-primary" value="@if(isset($vendorTransaction))
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
            url: "{{URL::asset('/')}}" + "getvendorCurrency/" + value,
            method: 'GET',

            success: function(data) {
                $("#vendor_account_id").empty();
                $("#vendor_account_id").append("<option value=''>حساب را انتخاب نمایید</option>");
                $('#vendor_account_id').html(data.html);

            }


        });

    }


    @isset($vendorTransaction)
    $.ajax({
        type: 'POST',
        url: "{{URL::asset('/')}}" + "getvendorCurrency/" + {
            !!$vendorTransaction - > vendor_id!!
        },
        method: 'GET',

        success: function(response) {
            $("#vendor_account_id").empty();
            $("#vendor_account_id").append("<option value=''>حساب را انتخاب نمایید</option>");
            $('#vendor_account_id').html(response.html);

            $("#vendor_account_id option").each(function() {
                // console.log('Text:-' + this.text + '  Value:-' + this.value);
                if ($(this).val() == {
                        !!$vendorTransaction - > vendor_currency_id!!
                    }) {
                    $(this).attr("selected", "selected");
                }
            });

        }
    });

    $.ajax({
        url: "{{URL::asset('/')}}" + "findCurrency/" + {
            !!$vendorTransaction - > account_id!!
        } + '/' + {
            !!$vendorTransaction - > vendor_currency_id!!
        },
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
</script>
@endsection

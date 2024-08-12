@extends('layouts.app')

@section('title', 'Account Transfer')
@section('content')

<div class="card mt-4">
    <div class="card-header d-flex justify-content-between">
        <h4 class="card-title">
            @if(isset($accountTransfer))
            {{ __('home.edit_transfer') }}
            @else
            {{ __('home.new_transfer') }}
            @endif
        </h4>
        @if (session()->has('success') || session()->has('error') )
        @include('layouts.partials.components.alert')
        @endif
        <a href="{{route('account_transfer.index')}}" class="btn btn-primary">{{ __('home.all_transfer') }}</a>

    </div>
    <div class="card-body ">
        <form action="{{isset($accountTransfer) ? route('account_transfer.update', $accountTransfer) : route('account_transfer.store')}}" method="POST" enctype="multipart/form-data">
            @csrf
            @if(isset($accountTransfer))
            @method('PUT')
            @else
            @method('POST')
            @endif

            <div class="form-row mb-3">


                <div class="col-xl-4 mb-3">
                    <label for="validationServer04">{{ __('home.from_account') }}</label>
                    <select class="form-selects form-control @error('account') {{'is-invalid'}} @enderror" onchange="showData(this.value);" id="from_account" aria-describedby="validationServer04Feedback" required name="from_account">
                        <option selected disabled value="">Choose...</option>
                        @foreach($accounts as $account)
                        <option value="{{$account->id}}" @if(isset($accountTransfer)) @if($accountTransfer->sender_account_id  == $account->id) selected = 'selected' @endif @endif data-currency="{{ $account->currency_id }}"> {{ $account->name }} - {{ $account->currency->name }} - {{ $account->amount }}</option>
                        @endforeach
                    </select>
                    @error('account_id')
                    <div id="" class="invalid-feedback">{{$message}}</div>
                    @enderror
                </div>


                <div class="col-xl-4 mb-3">
                    <label for="validationServer01">{{ __('home.amount') }}</label>
                    <input type="number" step="0.01" class="form-control @error('amount') {{'is-invalid'}} @enderror" onkeyup="calculate()" id="amount" name="amount" value="{{isset($accountTransfer) ? $accountTransfer->amount : old('amount')}}">
                    @error('amount')
                    <div id="" class="invalid-feedback">{{$message}}</div>
                    @enderror
                </div>
                <div class="col-xl-4 mb-3">
                    <label for="validationServer04">{{ __('home.to_account') }}</label>
                    <select class="form-control " name="to_account" id="to_account" onchange="showHiddenFields(); calculate()">
                        <option selected disabled value="">Choose...</option>

                    </select>
                    @error('account_id')
                    <div id="" class="invalid-feedback">{{$message}}</div>
                    @enderror
                </div>
                @if ($settings->date_type=='shamsi')
                <div class="col-xl-4 mb-3">
                    <label for="validationServer01">{{ __('home.date') }}</label>
                    <input type="text" class="form-control form-control @error('date') {{'is-invalid'}} @enderror" name="shamsi_date" autocomplete="off" id="dates" value="{{isset($accountTransfer) ? $accountTransfer->date : old('date')}}">

                    @error('date')
                    <div id="" class="invalid-feedback">{{$message}}</div>
                    @enderror
                </div>
                @else
                <div class="col-xl-4 mb-3">
                    <label for="validationServer01">{{ __('home.date') }}</label>
                    <input type="date" class="form-control @error('date') {{'is-invalid'}} @enderror" id="date" name="miladi_date" value="{{ isset($accountTransfer) ? $accountTransfer->miladi_date : date('Y-m-d') }}">
                    @error('date')
                    <div id="" class="invalid-feedback">{{$message}}</div>
                    @enderror
                </div>
                @endif
                <div class="col-xl-4 mb-3" id="exchange_rate_div" >
                    <label for="validationServer01">{{ __('home.rate') }}</label>
                    <input type="number" step="0.01" class="form-control " id="rate" name="rate" value="1" onkeyup="calculate()">
                    @error('rate')
                    <div id="" class="invalid-feedback">{{$message}}</div>
                    @enderror
                </div>

                <input type="hidden"  class="form-control " id="exchange_type" name="exchange_type" value="multiply" >

                <div class="col-xl-4 mb-3" id="total_div" >
                    <label for="validationServer01">{{ __('home.total') }}</label>
                    <input type="number" step="0.01" class="form-control " readonly id="total" name="total" value="{{isset($expense) ? $expense->main_amount : old('total')}}">
                    @error('total')
                    <div id="" class="invalid-feedback">{{$message}}</div>
                    @enderror
                </div>

                <div class="col-12 col-sm-12">
                    <label class="" for="inlineFormInputGroup">{{__('home.description')}}</label>
                    <textarea name="description" class="form-control" id="description">{{isset($accountTransfer) ? $accountTransfer->description: old('description')}}</textarea>

                    @error('address')
                    <span class="alert text-danger">{{$message}}</span>
                    @enderror
                </div>
            </div>

            <div class="form-footer mt-2">
                <input type="submit" class="btn btn-primary" value="@if(isset($accountTransfer))
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

    function showData(value) {
        $.ajax({
            url: "{{URL::asset('/')}}" + "getOtherAccounts/" + value,
            method: 'GET',
            success: function(data) {
                $("#to_account").empty();
                $("#to_account").append("<option value=''>حساب را انتخاب نمایید</option>");
                $('#to_account').html(data.html);
            }
        });
    }

    function showHiddenFields(callback) {
        var from_currency = $('#from_account').find('option:selected').data('currency');
        var to_currency = $('#to_account').find('option:selected').data('treasury');
        from = $('#from_account').val();
        to = $('#to_account').val();
        if (from_currency != to_currency) {
            $.ajax({
                url: '/getFromToRates/' + from + '/' + to,
                type: 'GET',
                success: function(response) {
                    var data = response.rate;
                    $('#rate').val(data.rate);
                    $('#exchange_type').val(data.operation);
                    if (typeof callback === "function") {
                        callback();
                    }
                },
                error: function(xhr, status, error) {
                    console.error(error);
                }
            });
        } else {
            if (typeof callback === "function") {
                callback();
            }
        }
    }

    function calculate() {
        var amount = parseFloat($('#amount').val());
        var rate = parseFloat($('#rate').val() || 1);
        var exchange_type = $('#exchange_type').val();

        var from_currency = $('#from_account').find('option:selected').data('currency');
        var to_currency = $('#to_account').find('option:selected').data('treasury');

        var total = 0;

        if (from_currency == to_currency) {
            total = amount;
            $('#rate').val(1);
        } else {
            if (exchange_type == 'multiply') {
                total = amount * rate;
            } else {
                total = amount / rate;
            }
        }
        $('#total').val(total.toFixed(2));
    }

    $(document).on('change', '#to_account', function() {
        showHiddenFields(calculate);
    });

    $(document).on('keyup', '#amount, #rate', function() {
        calculate();
    });

    @isset($accountTransfer)
    $.ajax({
        type: 'POST',
        url: "{{URL::asset('/')}}" + "getOtherAccounts/" + {!! $accountTransfer->sender_account_id !!},
        method: 'GET',
        success: function(response) {
            $("#to_account").empty();
            $("#to_account").append("<option value=''>حساب را انتخاب نمایید</option>");
            $('#to_account').html(response.html);

            $("#to_account option").each(function() {
                if ($(this).val() == {!! $accountTransfer->receiver_account_id !!}) {
                    $(this).attr("selected", "selected");
                }
            });
        }
    });
    @endisset
</script>

@endsection

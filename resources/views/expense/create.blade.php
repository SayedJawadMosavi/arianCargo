@extends('layouts.app')

@section('title', 'New Exepense')
@section('content')

{{-- @dd($settings->currency->id); --}}
<div class="card mt-4">
    <div class="card-header d-flex justify-content-between">
        <h4 class="card-title">
            @if(isset($expense))
            {{ __('home.edit_expense') }}
            @else
            {{ __('home.new_expense') }}
            @endif
        </h4>
        <a href="{{route('expense.index')}}" class="btn btn-primary">{{ __('home.all_expenses') }}</a>
        @if (session()->has('success') || session()->has('error') )
            @include('layouts.partials.components.alert')
        @endif
    </div>
    <div class="card-body ">
        <form action="{{isset($expense) ? route('expense.update', $expense) : route('expense.store')}}" method="POST" enctype="multipart/form-data">
            @csrf
            @if(isset($expense))
            @method('PUT')
            @else
            @method('POST')
            @endif
            {{-- @dd($main_currency) --}}
            <div class="form-row mb-3">
                <div class="col-xl-4 mb-3">
                    <label for="validationServer01">{{ __('home.main_currency') }}</label>
                    <input type="text" readonly class="form-control " id="main_currency" name="main_currency" value="{{ $main_currency->currency->name ?? '' }}">
                    <input type="hidden" readonly class="form-control " id="main_currency_id" name="main_currency_id" value="{{ $main_currency->currency->id ?? '' }}">
                    <input type="hidden" readonly class="form-control " id="account_currency" name="account_currency" value="">
                    @error('main_currency')
                    <div id="" class="invalid-feedback">{{$message}}</div>
                    @enderror
                </div>
                <div class="col-xl-4 mb-3">
                    <label for="validationServer01">{{ __('home.description') }}</label>
                    <input type="text" class="form-control @error('name') {{'is-invalid'}} @enderror" id="name" name="name" value="{{isset($expense) ? $expense->description : old('name')}}" autocomplete="off">
                    @error('name')
                    <div id="" class="invalid-feedback">{{$message}}</div>
                    @enderror
                </div>
                <div class="col-12 col-sm-4 col-md-4 col-md-2">
                    <label class="" for="inlineFormInputGroup">{{__('home.type')}}</label>
                    <select class="form-control " name="type" id="type" onchange="showData(this.value);">
                        <option value="income" @if(isset($expense)) @if($expense->type == 'income') selected = 'selected' @endif @endif >{{__('home.income')}}</option>
                        <option value="expense" @if(isset($expense)) @if($expense->type == 'expense') selected = 'selected' @endif @endif >{{__('home.expense')}}</option>
                    </select>
                    @error('type')
                    <span class="alert text-danger">{{$message}}</span>
                    @enderror
                </div>
                <div class="col-xl-4 mb-3">
                    <label for="validationServer04">{{ __('home.category') }}
                        <span class="badge bg-primary">
                            <a class="" data-bs-effect="effect-sign" data-bs-toggle="modal" href="#modaldemo8"><span class="badge bg-primary"> <i class="fe fe-plus "></i></span></a>
                    </label>
                    <select class="form-select form-control select2 @error('category_id') {{'is-invalid'}} @enderror" id="validationServer04" aria-describedby="validationServer04Feedback" required name="category_id">
                        <option selected disabled value="">Choose...</option>
                        @if(isset($expense))
                        @foreach($categories as $category)
                        <option value="{{$category->id}}" @if(isset($expense)) @if($expense->expense_category_id == $category->id) selected = 'selected' @endif @endif >{{$category->name}}</option>
                        @endforeach
                        @endif
                    </select>
                    @error('category_id')
                    <div id="" class="invalid-feedback">{{$message}}</div>
                    @enderror
                </div>
                <div class="col-xl-4 mb-3">
                    <label for="validationServer04">{{ __('home.account') }}</label>
                    <select class="form-selects form-control @error('account_id') {{'is-invalid'}} @enderror" name="account_id" onchange="showHide(this.value);" id="validationServer04" aria-describedby="validationServer04Feedback" required>
                        <option>{{ __('home.please_select') }}</option>
                        @foreach($accounts as $account)
                        <option value="{{$account->id}}" @if(isset($expense)) @if($expense->account_id == $account->id) selected = 'selected' @endif @endif> {{ $account->name }} - {{ $account->currency->name }} - {{ $account->amount }}</option>
                        @endforeach
                    </select>
                    @error('account_id')
                    <div id="" class="invalid-feedback">{{$message}}</div>
                    @enderror
                </div>
                <div class="col-xl-4 mb-3">
                    <label for="validationServer01">{{ __('home.amount') }}</label>
                    <input type="number" step="0.01" class="form-control @error('amount') {{'is-invalid'}} @enderror" id="amount" name="amount" value="{{isset($expense) ? $expense->amount : old('amount', 0)}}" onkeyup="calculate()">
                    @error('amount')
                    <div id="" class="invalid-feedback">{{$message}}</div>
                    @enderror
                </div>
                <div class="col-xl-4 mb-3" id="exchange_rate_div" style="display: none;">
                    <label for="validationServer01">{{ __('home.rate') }}</label>
                    <input type="number" step="0.01" class="form-control " id="rate" name="rate" value="{{isset($expense) ? $expense->rate : old('rate')}}" onkeyup="calculate()">
                    @error('rate')
                    <div id="" class="invalid-feedback">{{$message}}</div>
                    @enderror
                </div>

                <input type="hidden"  class="form-control " id="exchange_type" name="exchange_type" value="{{isset($expense) ? $expense->rate : old('rate')}}">

                <div class="col-xl-4 mb-3" id="total_div" style="display: none;">
                    <label for="validationServer01">{{ __('home.main_currency_total') }}</label>
                    <input type="number" step="0.01" class="form-control " readonly id="main_amount" name="main_amount" value="{{isset($expense) ? $expense->main_amount : old('main_amount')}}">
                    @error('main_amount')
                    <div id="" class="invalid-feedback">{{$message}}</div>
                    @enderror
                </div>
                @if ($settings->date_type=='shamsi')
                <div class="col-xl-4 mb-3">
                    <label for="validationServer01">{{ __('home.date') }}</label>
                    <input type="text" class="form-control form-control @error('date') {{'is-invalid'}} @enderror" name="shamsi_date" autocomplete="off" id="dates" value="{{isset($expense) ? $expense->date : old('date')}}">

                    @error('date')
                    <div id="" class="invalid-feedback">{{$message}}</div>
                    @enderror
                </div>
                @else
                <div class="col-xl-4 mb-3">
                    <label for="validationServer01">{{ __('home.date') }}</label>
                    <input type="date" class="form-control @error('date') {{'is-invalid'}} @enderror" id="date" name="miladi_date" value="{{ isset($expense) ? $expense->miladi_date : date('Y-m-d') }}">
                    @error('date')
                    <div id="" class="invalid-feedback">{{$message}}</div>
                    @enderror
                </div>
                @endif

            </div>

            <div class="form-footer mt-2">
                <input type="submit" class="btn btn-primary" value="@if(isset($expense))
                    {{ __('home.update') }}
                @else
                    {{ __('home.save') }}
                @endif">
            </div>

        </form>

    </div>
</div>
<div class="modal fade" id="modaldemo8">
    <div class="modal-dialog modal-dialog-centered text-center modal-sm" role="document">
        <div class="modal-content modal-content-demo">
            <div class="modal-header">
                <h6 class="modal-title">{{ __('home.new_category') }}</h6><button aria-label="Close" class="btn-close" data-bs-dismiss="modal"><span aria-hidden="true">&times;</span></button>
            </div>
            <form action="{{route('expense_category.store')}}" method="POST" enctype="multipart/form-data">
                <div class="modal-body">

                    @csrf

                    @method('POST')


                    <div class="form-row mb-3">

                        <div class="col-xl-12 mb-3">
                            <label for="validationServer01">{{ __('home.name') }}</label>
                            <input type="text" class="form-control @error('name') {{'is-invalid'}} @enderror" id="name" name="name" value="">
                            <input type="hidden" class="form-control" name="types" value="expense_income">
                            @error('name')
                            <div id="" class="invalid-feedback">{{$message}}</div>
                            @enderror
                        </div>
                        <div class="col-12 col-sm-12 col-md-12 col-md-2">
                            <label class="" for="inlineFormInputGroup">{{__('home.type')}}</label>
                            <select class="form-control " name="type" id="type">
                                <option value="income" @if(isset($category)) @if($category->type == 'income') selected = 'selected' @endif @endif >{{__('home.income')}}</option>
                                <option value="expense" @if(isset($category)) @if($category->type == 'expense') selected = 'selected' @endif @endif >{{__('home.expense')}}</option>
                            </select>
                            @error('type')
                            <span class="alert text-danger">{{$message}}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-footer mt-2">
                        <input type="submit" class="btn btn-primary" value="{{ __('home.send') }}">
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>
@endsection
@section('pagescript')
<script>
      function showHide(value) {
        $.ajax({
            url: "{{URL::asset('/')}}" + "get-currency/" + value,
            method: 'GET',
            success: function(data) {
                $("#account_currency").val(data.data.currency_id)
                if ({!! $settings->currency->id !!} !=  data.data.currency_id) {
                    $("#exchagne_type_div").css('display', "block")
                    $("#total_div").css('display', "block")
                    $("#exchange_rate_div").css('display', "block")
                } else {
                    $("#exchagne_type_div").css('display', "none")
                    $("#total_div").css('display', "none")
                    $("#exchange_rate_div").css('display', "none")
                }
            }
        });

        $.ajax({
            url: '/get_latest_exchange_rate/' + value,
            type: 'GET',
            success: function(response) {
                var data = response.rate;
                $('#rate').val(data.rate);
                $('#exchange_type').val(data.operation);
            },
            error: function(xhr, status, error) {
                // console.error(error);
            }
        });

    }
    @isset($expense)
    $.ajax({
            url: "{{URL::asset('/')}}" + "get-currency/" + {!! $expense->account_id !!},
            method: 'GET',
            success: function(data) {
                $("#account_currency").val(data.data.currency_id)
                if ({!! $settings->currency->id ?? '' !!} !=  data.data.currency_id) {
                    $("#exchagne_type_div").css('display', "block")
                    $("#total_div").css('display', "block")
                    $("#exchange_rate_div").css('display', "block")
                } else {
                    $("#exchagne_type_div").css('display', "none")
                    $("#total_div").css('display', "none")
                    $("#exchange_rate_div").css('display', "none")

                }
            }
        });

        $.ajax({
            url: '/get_latest_exchange_rate/' + {!! $expense->account_id !!},
            type: 'GET',
            success: function(response) {
                var data = response.rate;
                $('#rate').val(data.rate);
                $('#exchange_type').val(data.operation);
            },
            error: function(xhr, status, error) {
                // console.error(error);
            }
        });
    @endisset

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
    showData('income');

    function showData(value) {


        $.ajax({
            url: "{{URL::asset('/')}}" + "expense-category/" + value,
            method: 'GET',
            success: function(data) {
                $('.form-select').html(data.data);

            }
        });

    }
    function calculate(value) {

        var amount=$('#amount').val()
        var rate=$('#rate').val()
        var exchange_type=$('#exchange_type').val()

        if (exchange_type === "divide") {
            $('#main_amount').val((amount / rate).toFixed(2));
        } else {
            $('#main_amount').val((amount * rate).toFixed(2));
        }

    }
</script>
@endsection

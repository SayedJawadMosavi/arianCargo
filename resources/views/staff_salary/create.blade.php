@extends('layouts.app')

@section('title', 'New Transaction')
@section('content')

<div class="card mt-4">
    <div class="card mt-4">
        <div class="card-header d-flex justify-content-between">
            <h4 class="card-title">
                @if(isset($staff_salary))
                {{ __('home.edit_transaction') }}
                @else
                {{ __('home.new_transaction') }}
                @endif
            </h4>


        </div>
        @if (session()->has('success') || session()->has('error') )
        @include('layouts.partials.components.alert')
        @endif
        @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif
        <div class="card-body ">
            <form action="{{isset($staff_salary) ? route('staff_salary.update', $staff_salary->id) : route('staff_salary.store')}}" method="POST" enctype="multipart/form-data">
                @csrf
                @if(isset($staff_salary))
                @method('PUT')
                @else
                @method('POST')
                @endif
                <input type="hidden" name="salary" id="salary" value="">
                <div class="form-row mb-3">
                    <div class="col-xl-3 mb-3">
                        <label for="validationServer04">{{ __('home.account') }}</label>
                        <select class="form-selects form-control @error('account') {{'is-invalid'}} @enderror" id="account_id" aria-describedby="validationServer04Feedback" required name="account_id">
                            <option selected disabled value="">Choose...</option>
                            @foreach($accounts as $account)
                            <option value="{{$account->id}}" @if(isset($staff_salary)) @if($staff_salary->account_id == $account->id) selected = 'selected' @endif @endif> {{ $account->name }} - {{ $account->currency->name }} - {{ $account->amount }}</option>
                            @endforeach
                        </select>
                        @error('account_id')
                        <div id="" class="invalid-feedback">{{$message}}</div>
                        @enderror
                    </div>

                    <div class="col-xl-3 mb-3">
                        <label for="validationServer04">{{ __('home.staff') }}</label>
                        <select class="form-selects form-control select2 @error('staff') {{'is-invalid'}} @enderror" onchange="showLoan(this.value);" id="staff_id" aria-describedby="validationServer04Feedback" required name="staff_id">
                            <option selected disabled value="">Choose...</option>
                            @foreach($staffs as $staff)
                            <option value="{{$staff->id}}" @if(isset($staff_salary)) @if($staff_salary->staff_id == $staff->id) selected = 'selected' @endif @endif> {{ $staff->name }} - >salary ( {{ $staff->salary }})</option>
                            @endforeach
                        </select>
                        @error('staff_id')
                        <div id="" class="invalid-feedback">{{$message}}</div>
                        @enderror
                    </div>
                    <div class="col-xl-3 mb-3" id="loans" style="display: none;">
                        <label for="validationServer01">{{ __('home.loan') }}</label>
                        <input type="text" class="form-control @error('loan') {{'is-invalid'}} @enderror" id="loan" name="loan" value="{{isset($staff_salary) ? $staff_salary->loan : old('loan', 0)}}">
                        @error('loan')
                        <div id="" class="invalid-feedback">{{$message}}</div>
                        @enderror
                    </div>
                    <div class="col-xl-3 mb-3">
                        <label for="validationServer01">{{ __('home.payable') }}</label>
                        <input type="number" step="0.01" class="form-control @error('payable') {{'is-invalid'}} @enderror" id="payable" name="payable" value="{{isset($staff_salary) ? $staff_salary->payable : old('payable',0)}}">
                        @error('payable')
                        <div id="" class="invalid-feedback">{{$message}}</div>
                        @enderror
                    </div>

                    <div class="col-xl-3 mb-3">
                        <label for="validationServer01">{{ __('home.deduction') }}</label>
                        <input type="number" step="0.01" class="form-control @error('amount') {{'is-invalid'}} @enderror" onkeyup="calculate(this.value)" id="amount" name="amount" value="{{isset($staff_salary) ? $staff_salary->deduction : old('deduction')}}">
                        @error('amount')
                        <div id="" class="invalid-feedback">{{$message}}</div>
                        @enderror
                    </div>


                    <div class="col-xl-3 mb-3">
                        <label for="validationServer01">{{ __('home.paid') }}</label>
                        <input type="number" step="0.01" class="form-control @error('paid') {{'is-invalid'}} @enderror" id="paid" name="paid" value="{{isset($staff_salary) ? $staff_salary->paid : old('paid', 0)}}">
                        @error('paid')
                        <div id="" class="invalid-feedback">{{$message}}</div>
                        @enderror
                    </div>

                    @if ($settings->date_type=='shamsi')
                    <div class="col-xl-3 mb-3">
                        <label for="validationServer01">{{ __('home.date') }}</label>
                        <input type="text" class="form-control form-control @error('date') {{'is-invalid'}} @enderror" name="shamsi_date" autocomplete="off" id="dates" value="{{isset($staff_salary) ? $staff_salary->date : old('date')}}">

                        @error('date')
                        <div id="" class="invalid-feedback">{{$message}}</div>
                        @enderror
                    </div>
                    @else
                    <div class="col-xl-3 mb-3">
                        <label for="validationServer01">{{ __('home.date') }}</label>
                        <input type="date" class="form-control @error('date') {{'is-invalid'}} @enderror" id="date" name="miladi_date" value="{{ isset($staff_salary) ? $staff_salary->miladi_date : date('Y-m-d') }}">
                        @error('date')
                        <div id="" class="invalid-feedback">{{$message}}</div>
                        @enderror
                    </div>
                    @endif
                    <div class="col-12 col-sm-12">
                        <label class="" for="inlineFormInputGroup">{{__('home.description')}}</label>
                        <textarea name="description" class="form-control" id="description">{{isset($staff_salary) ? $staff_salary->description: old('description')}}</textarea>

                        @error('address')
                        <span class="alert text-danger">{{$message}}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-footer mt-2">
                    <input type="submit" class="btn btn-primary" value="@if(isset($staff_salary))
                    {{ __('home.update') }}
                @else
                    {{ __('home.save') }}
                @endif">
                </div>

            </form>

        </div>
    </div>

    {{-- card-body --}}
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
    function showLoan(value) {
        type = 'treasury';
        $.ajax({
            url: "{{URL::asset('/')}}" + "getstaffloan/" + value,
            method: 'GET',
            success: function(data) {

                $('#loan').val(Math.abs(data.staff_laon.loan));
                $('#salary').val(data.staff_laon.salary);
                if (data.staff_laon.loan < 0) {
                    $('#loans').css('display', 'block')

                } else {
                    $('#loans').css('display', 'none')

                }

            }
        });

    }
    @isset($staff_salary)

        $.ajax({
            url: "{{URL::asset('/')}}" + "getstaffloan/" + {!! $staff_salary->staff_id !!},
            method: 'GET',
            success: function(data) {

                $('#loan').val(Math.abs(data.staff_laon.loan));
                $('#salary').val(data.staff_laon.salary);
                if (data.staff_laon.loan < 0) {
                    $('#loans').css('display', 'block')

                } else {
                    $('#loans').css('display', 'none')

                }

            }
        });


    @endisset
    function calculate() {
        var salaryInput = $('#payable');
        var amountInput = $('#amount');
        var paidInput = $('#paid');

        // Get values from inputs and convert to floats
        var salary = parseFloat(salaryInput.val()) || 0;
        var amount = parseFloat(amountInput.val()) || 0;


        // Perform the calculation
        var paid = salary - amount;

        // Update the paid input field
        paidInput.val(paid.toFixed(2)); // Round to 2 decimal places (adjust as needed)
    }
</script>
@endsection

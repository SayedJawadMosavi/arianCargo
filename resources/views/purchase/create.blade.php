@extends('layouts.app')

@section('title', 'New purchase')
@section('content')

    <div class="card mt-4">

        @if (session()->has('success') || session()->has('error') )
            @include('layouts.partials.components.alert')
        @endif

        <div class="card-header d-flex justify-content-between">
            <h4 class="card-title">
                @if(isset($purchase))
                    {{ __('home.edit_purchase') }}
                @else
                    {{ __('home.new_purchase') }}
                @endif
            </h4>
            <a href="{{route('purchase.index')}}" class="btn btn-primary">{{ __('home.all_purchases') }}</a>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
        <div class="card-body ">
            <form action="{{isset($purchase) ? route('purchase.update', $purchase) : route('purchase.store')}}" method="POST" enctype="multipart/form-data">
                @csrf
                @if(isset($purchase))
                    @method('PUT')
                @else
                    @method('POST')
                @endif

                <div class="form-row mb-3">

                    <div class="col-xl-4 mb-3">
                        <label for="validationServer04">{{ __('home.vendor') }} <span class="badge bg-primary">
                            <a class="" data-bs-effect="effect-sign" data-bs-toggle="modal" href="#modaldemo8"><span class="badge bg-primary"> <i class="fe fe-plus "></i></span></a>
                        </label>
                        <select class="form-select form-control select2 @error('vendor_id') {{'is-invalid'}} @enderror" id="validationServer04" aria-describedby="validationServer04Feedback" required name="vendor_id">
                            @foreach($vendors as $vendor)
                                <option value="{{$vendor->id}}" @if(isset($purchase)) @if($purchase->vendor_id == $vendor->id) selected = 'selected' @endif @endif >{{$vendor->company}}</option>
                            @endforeach
                        </select>
                        @error('vendor_id')
                        <div id="" class="invalid-feedback">{{$message}}</div>
                        @enderror
                    </div>
                    <div class="col-xl-4 mb-3">
                        <label for="validationServer04">{{ __('home.account') }}</label>
                        <select class="form-select form-control @error('account') {{'is-invalid'}} @enderror" id="validationServer04" aria-describedby="validationServer04Feedback" required name="account_id">
                            @foreach($accounts as $account)
                                <option value="{{$account->id}}" @if(isset($purchase)) @if($purchase->account_id == $account->id) selected = 'selected' @endif @endif> {{ $account->name }} - {{ $account->currency->name }} - {{ $account->amount }}</option>
                            @endforeach
                        </select>
                        @error('account_id')
                        <div id="" class="invalid-feedback">{{$message}}</div>
                        @enderror
                    </div>
                    @if ($settings->date_type=='shamsi')
                    <div class="col-xl-4 mb-3">
                        <label for="validationServer01">{{ __('home.date') }}</label>
                        <input type="text" class="form-control form-control @error('date') {{'is-invalid'}} @enderror" name="shamsi_date" autocomplete="off" id="dates" value="{{isset($purchase) ? $purchase->shamsi_date : old('date')}}">

                        @error('date')
                        <div id="" class="invalid-feedback">{{$message}}</div>
                        @enderror
                    </div>
                    @else
                    <div class="col-xl-4 mb-3">
                        <label for="validationServer01">{{ __('home.date') }}</label>
                        <input type="date" class="form-control @error('date') {{'is-invalid'}} @enderror" id="date"  name="miladi_date" value="{{ isset($purchase) ? $purchase->miladi_date : date('Y-m-d') }}">
                        @error('date')
                        <div id="" class="invalid-feedback">{{$message}}</div>
                        @enderror
                    </div>
                    @endif

                    <div class="col-xl-2 mb-3">
                        <label for="validationServer01">{{ __('home.bill') }}</label>
                        <input type="text" class="form-control @error('bill') {{'is-invalid'}} @enderror" id="bill"  name="bill" value="{{isset($purchase) ? $purchase->bill : old('bill')}}" autocomplete="off">
                        @error('bill')
                        <div id="" class="invalid-feedback">{{$message}}</div>
                        @enderror
                    </div>
                    <div class="col-xl-10 mb-3">
                        <label for="validationServer01">{{ __('home.description') }}</label>
                        <input type="text" class="form-control @error('description') {{'is-invalid'}} @enderror" id="description"  name="description" value="{{isset($purchase) ? $purchase->description : old('description')}}" autocomplete="off">
                        @error('description')
                        <div id="" class="invalid-feedback">{{$message}}</div>
                        @enderror
                    </div>

                </div>

                @if(!isset($purchase))
                <div class="form-row mb-3">

                    <div class="col-sm-12 mt-3">
                        <h4>{{__('home.products')}}</h4>
                        <hr>
                    </div>
                    <div class="col-sm-12">
                        <div class="table-responsive">
                            <span id="result"></span>
                            <table class="table table-bordered table-striped" id="user_table">
                                <thead>
                                    <tr>
                                        <th width="35%">{{__('home.product')}}</th>
                                        <th width="15%">{{__('home.quantity')}}</th>
                                        <th width="15%">{{__('home.price')}}</th>
                                        <th width="15%" class="d-none">{{__('home.expense')}}</th>
                                        <!-- <th width="15%">{{__('home.sell')}}</th> -->
                                        <th width="15%">{{__('home.total')}}</th>
                                        <th width="10%">{{__('home.action')}}</th>
                                    </tr>
                                </thead>
                                <tbody id="tbody">

                                </tbody>

                            </table>
                        </div>
                    </div>

                </div>
                @endif
                <div class=" row mb-4" dir="{{App::getLocale() == 'en' ? 'ltr' : 'rtl'}}">
                    <label class="col-md-2 {{App::getLocale() == 'en' ? 'offset-sm-7' : ''}}  form-label text-right fw-bold">{{ __('home.grand_total') }}</label>
                    <div class="col-md-3">
                        <input type="number" step="0.01" id="total" name="total" class="form-control" readonly="" value="{{isset($purchase) ? $purchase->total : old('total', 0)}}">
                    </div>
                </div>
                <div class=" row mb-4" dir="{{App::getLocale() == 'en' ? 'ltr' : 'rtl'}}">
                    <label class="col-md-2 {{App::getLocale() == 'en' ? 'offset-sm-7' : ''}}  form-label text-right fw-bold">{{ __('home.paid') }}</label>
                    <div class="col-md-3">
                        <input type="number" step="0.01" id="paid" name="paid" class="form-control"   value="{{isset($purchase) ? $purchase->paid : old('paid', 0)}}">
                    </div>
                </div>
                <div class=" row mb-4" dir="{{App::getLocale() == 'en' ? 'ltr' : 'rtl'}}">
                    <label class="col-md-2 {{App::getLocale() == 'en' ? 'offset-sm-7' : ''}}  form-label text-right fw-bold">{{ __('home.balance') }}</label>
                    <div class="col-md-3">
                        <input type="number" step="0.01" id="balance" name="balance" class="form-control" readonly=""  value="{{isset($purchase) ? $purchase->balance : old('balance', 0)}}">
                    </div>
                </div>



                <div class="form-footer mt-2">
                    <input type="submit" class="btn btn-primary" value="@if(isset($purchase))
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
    $(document).ready(function() {

        var count = 1;

        dynamic_field(count);

        function dynamic_field(number) {
            html = '<tr>';
            html += "<td>" +
                '<select class="form-select form-control select2"  name="product[]" id="to_currency">' +
                +"@foreach ($products as $obj)" +
                "<option value='{{$obj->id}}' >" + '{{$obj->name}}' + "</option>" +
                "@endforeach" +
                "</select> " +
                "</td>"
            html += '<td><input type="number" name="quantity[]" oninput="calculate()" class="form-control" value="0" /></td>';
            html += '<td><input type="number" step="0.01" name="cost[]"  oninput="calculate()" class="form-control" value="0.00" /></td>';
            html += '<td class="d-none"><input type="number" name="expense[]" oninput="calculate()" class="form-control" value="0" /></td>';
            // html += '<td><input type="number" name="sell[]" class="form-control" value="0" /></td>';
            html += '<td><input type="number" name="total[]"  step="0.01" oninput="calculate()" class="form-control" value="0" /></td>';
            if (number > 1) {
                html += '<td><button type="button" name="remove" id="" class="btn btn-danger remove"> <i class="fa fa-minus"></i></button></td></tr>';
                $('#tbody').append(html);
            } else {
                html += '<td><button type="button" name="add" id="add" class="btn btn-success btn btn-primary"> <i class="fa fa-plus"></i></button></td></tr>';
                $('#tbody').html(html);
            }
            $('.select2').select2();

        }

        $(document).on('click', '#add', function() {
            count++;
            dynamic_field(count);
        });

        $(document).on('click', '.remove', function() {
            count--;
            $(this).closest("tr").remove();


            var grandTotal = 0;
            var balance = 0;

            $('#tbody tr').each(function() {
                var quantity = parseFloat($(this).find('[name="quantity[]"]').val()) || 0;
                var cost = parseFloat($(this).find('[name="cost[]"]').val()) || 0;
                var expense = parseFloat($(this).find('[name="expense[]"]').val()) || 0;

                var total = quantity * (cost + expense);
                grandTotal += total;

                // Update the total input field in the current row
                $(this).find('[name="total[]"]').val(total.toFixed(2));
            });

            // Update the grand_total input field
            $('#total').val(grandTotal.toFixed(2));

        });


    });

    function calculate() {
        var grandTotal = 0;
        var balance = 0;

        $('#tbody tr').each(function() {
            var quantity = parseFloat($(this).find('[name="quantity[]"]').val()) || 0;
            var cost = parseFloat($(this).find('[name="cost[]"]').val()) || 0;
            var expense = parseFloat($(this).find('[name="expense[]"]').val()) || 0;

            var total = quantity * (cost + expense);
            grandTotal += total;

            // Update the total input field in the current row
            $(this).find('[name="total[]"]').val(total.toFixed(2));
        });

        // Update the grand_total input field
        $('#total').val(grandTotal.toFixed(2));
        $('#balance').val(grandTotal.toFixed(2));
    }

    $('#paid').keyup(function(){

        var total = parseFloat($('#total').val());
        var paid = parseFloat($('#paid').val());

        $('#balance').val(total -  paid);
    });

    $('.select2').select2();

</script>





<div class="modal fade" id="modaldemo8">
    <div class="modal-dialog modal-dialog-centered text-center modal-lg" role="document">
        <div class="modal-content modal-content-demo">
            <div class="modal-header">
                <h6 class="modal-title">{{ __('home.new_vendor') }}</h6><button aria-label="Close" class="btn-close" data-bs-dismiss="modal"><span aria-hidden="true">&times;</span></button>
            </div>
            <form action="{{route('vendors.store')}}" method="POST" enctype="multipart/form-data">
                <div class="modal-body">

                    @csrf

                        @method('POST')


                    <div class="form-row mb-3">

                        <div class="col-xl-4 col-sm-4 mb-3">
                            <label for="validationServer01">{{ __('home.company') }}</label>
                            <input type="text" class="form-control @error('company') {{'is-invalid'}} @enderror" id="company"  name="company" \>
                            <input type="hidden" class="form-control" name="type" value="purchase">
                        </div>
                        <div class="col-xl-4 col-sm-4 mb-3">
                            <label for="validationServer01">{{ __('home.contact_person') }}</label>
                            <input type="text" class="form-control @error('contact_person') {{'is-invalid'}} @enderror" id="contact_person"  name="contact_person" >
                            @error('contact_person')
                            <div id="" class="invalid-feedback">{{$message}}</div>
                            @enderror
                        </div>

                        <div class="col-xl-4 col-sm-4 mb-3">
                            <label for="validationServer01">{{ __('home.mobile') }}</label>
                            <input type="text" class="form-control @error('mobile') {{'is-invalid'}} @enderror" id="mobile"  name="mobile">
                            @error('mobile')
                            <div id="" class="invalid-feedback">{{$message}}</div>
                            @enderror
                        </div>
                        <div class="col-xl-4 col-sm-4 mb-3">
                            <label for="validationServer01">{{ __('home.license') }}</label>
                            <input type="text" class="form-control @error('license') {{'is-invalid'}} @enderror" id="license"  name="license" >
                            @error('license')
                            <div id="" class="invalid-feedback">{{$message}}</div>
                            @enderror
                        </div>
                        <div class="col-xl-8 col-sm-12 mb-3">
                            <label for="validationServer01">{{ __('home.address') }}</label>
                            <input type="text" class="form-control @error('address') {{'is-invalid'}} @enderror" id="address"  name="address">
                            @error('address')
                            <div id="" class="invalid-feedback">{{$message}}</div>
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

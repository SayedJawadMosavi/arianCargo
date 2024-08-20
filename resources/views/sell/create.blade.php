@extends('layouts.app')

@section('title', 'New sell')
@section('content')

<div class="card mt-4">

    @if (session()->has('success') || session()->has('error') )
    @include('layouts.partials.components.alert')
    @endif

    <div class="card-header d-flex justify-content-between">
        <h4 class="card-title">
            @if(isset($sell))
            {{ __('home.edit_sell') }}
            @else
            {{ __('home.new_sell') }}
            @endif
        </h4>
        <a href="{{route('sell.index')}}" class="btn btn-primary">{{ __('home.all_sells') }}</a>

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
        <form action="{{isset($sell) ? route('sell.update', $sell) : route('sell.store')}}" method="POST" enctype="multipart/form-data">
            @csrf
            @if(isset($sell))
            @method('PUT')
            @else
            @method('POST')
            @endif

            <div class="form-row mb-3">
                <div class="col-xl-4 mb-3">
                    <label for="validationServer04">{{ __('home.sender') }}</label>
                    <select class="form-select form-control select2 @error('client_id') {{'is-invalid'}} @enderror" onchange="showData(this.value)" id="client_id" aria-describedby="validationServer04Feedback" required name="client_id">
                        <option> {{__('home.please_select')}}</option>
                        <option value="new"> {{__('home.new_customer')}}</option>
                        @foreach($clients as $client)

                        <option value="{{$client->id}}" @if(isset($sell)) @if($sell->client_id == $client->id) selected = 'selected' @endif @endif >{{$client->name}}</option>
                        @endforeach
                    </select>
                    @error('client_id')
                    <div id="" class="invalid-feedback">{{$message}}</div>
                    @enderror
                </div>
                <div class="col-xl-4 mb-3 my_div" style="display: none;" id="">
                    <label for="validationServer01">{{ __('home.name') }}</label>
                    <input type="text" class="form-control form-control" name="client_name" autocomplete="off" id="client_name" value="{{isset($sell) ? $sell->client_name : old('client_name')}}" autocomplete="off">
                    @error('client_name')
                    <div id="" class="invalid-feedback">{{$message}}</div>
                    @enderror
                </div>
                <div class="col-xl-4 mb-3 my_div" style="display: none;" id="">
                    <label for="validationServer01">{{ __('home.phone') }}</label>
                    <input type="text" class="form-control form-control" name="client_phone" autocomplete="off" id="client_phone" value="{{isset($sell) ? $sell->client_phone : old('client_phone')}}" autocomplete="off">
                    @error('client_phone')
                    <div id="" class="invalid-feedback">{{$message}}</div>
                    @enderror
                </div>
                <div class="col-xl-4 mb-3 my_div" style="display: none;" id="">
                    <label for="validationServer01">{{ __('home.tazkira_no') }}</label>
                    <input type="text" class="form-control form-control" name="client_tazkira_no" autocomplete="off" id="client_tazkira_no" value="{{isset($sell) ? $sell->client_tazkira_no : old('client_tazkira_no')}}" autocomplete="off">
                    @error('client_tazkira_no')
                    <div id="" class="invalid-feedback">{{$message}}</div>
                    @enderror
                </div>
                <div class="col-xl-4 mb-3 my_div" style="display: none;" id="">
                    <label for="validationServer01">{{ __('home.address') }}</label>
                    <input type="text" class="form-control form-control" name="client_address" autocomplete="off" id="client_address" value="{{isset($sell) ? $sell->client_address : old('client_address')}}" autocomplete="off">
                    @error('client_address')
                    <div id="" class="invalid-feedback">{{$message}}</div>
                    @enderror
                </div>

                @if(isset($sell))
                <div class="col-xl-4 mb-3">
                    <label for="validationServer04">{{ __('home.account') }}</label>
                    <select class="form-select form-control select2 @error('account_id') {{'is-invalid'}} @enderror" onchange="showData(this.value)" id="account_id" aria-describedby="validationServer04Feedback" required name="account_id">
                        <option> {{__('home.please_select')}}</option>
                        @foreach($accounts as $account)
                        <option value="{{$account->id}}" @if(isset($sell)) @if($sell->account_id == $account->id) selected = 'selected' @endif @endif >{{$account->name}}</option>
                        @endforeach
                    </select>
                    @error('account_id')
                    <div id="" class="invalid-feedback">{{$message}}</div>
                    @enderror
                </div>
                @else
                <div class="col-xl-4 mb-3">
                    <label for="validationServer04">{{ __('home.account') }}</label>
                    <input type="hidden" name="currency_id" id="currency_id">

                    <select class="form-select form-control @error('account') {{'is-invalid'}} @enderror" onchange="showCurrency(this.value)" id="account_id" aria-describedby="validationServer04Feedback" required name="account_id">

                    </select>
                    @error('account_id')
                    <div id="" class="invalid-feedback">{{$message}}</div>
                    @enderror
                </div>
                @endif
                <div class="col-xl-4 mb-3">
                    <label for="validationServer01">{{ __('home.rate') }}</label>
                    <input type="text" class="form-control form-control @error('rate') {{'is-invalid'}} @enderror" readonly name="rate" autocomplete="off" id="rate" value="1" step="0.001">
                    <input type="hidden" class="form-control form-control @error('operation') {{'is-invalid'}} @enderror" readonly name="operation" autocomplete="off" id="operation">
                    @error('operation')
                    <div id="" class="invalid-feedback">{{$message}}</div>
                    @enderror
                </div>
                @if ($settings->date_type=='shamsi')

                <div class="col-xl-3 mb-3">
                    <label for="validationServer01">{{ __('home.date') }}</label>
                    <input type="text" class="form-control form-control @error('date') {{'is-invalid'}} @enderror" name="shamsi_date" autocomplete="off" id="dates" value="{{isset($sell) ? $sell->shamsi_date : old('date')}}">
                    @error('date')
                    <div id="" class="invalid-feedback">{{$message}}</div>
                    @enderror
                </div>
                @else
                <div class="col-xl-3 mb-3">
                    <label for="validationServer01">{{ __('home.date') }}</label>
                    <input type="date" class="form-control @error('date') {{'is-invalid'}} @enderror" id="date" name="miladi_date" value="{{ isset($sell) ? $sell->miladi_date : date('Y-m-d') }}">
                    @error('date')
                    <div id="" class="invalid-feedback">{{$message}}</div>
                    @enderror
                </div>
                @endif
                <div class="col-xl-2 mb-3">
                    <label for="validationServer01">{{ __('home.bill') }}</label>
                    <input type="text" class="form-control form-control @error('bill') {{'is-invalid'}} @enderror" name="bill" autocomplete="off" id="bill" value="{{isset($sell) ? $sell->bill : old('bill')}}">
                    @error('bill')
                    <div id="" class="invalid-feedback">{{$message}}</div>
                    @enderror
                </div>
                <div class="col-xl-7 mb-3">
                    <label for="validationServer01">{{ __('home.description') }}</label>
                    <input type="text" class="form-control @error('description') {{'is-invalid'}} @enderror" id="description" name="description" value="{{isset($sell) ? $sell->description : old('description')}}" autocomplete="off">
                    @error('description')
                    <div id="" class="invalid-feedback">{{$message}}</div>
                    @enderror
                </div>

            </div>
            <fieldset class="mt-3">
                <legend> {{ __('home.receiver_info') }} </legend>
                <div class="form-row align-items-center">
                    <div class="col-md-3 mb-3">
                        <label for="validationServer01">نام</label>
                        <input type="text" class="form-control @error('relation_name') {{'is-invalid'}} @enderror" id="receiver_name" name="receiver_name" value="{{isset($sell) ? $sell->receiver_name : old('receiver_name')}}">
                        @error('relation_name')
                        <div id="" class="invalid-feedback">{{$message}}</div>
                        @enderror
                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="validationServer01"> {{ __('home.name') }} </label>
                        <input type="text" class="form-control @error('relation_phone') {{'is-invalid'}} @enderror" id="receiver_phone" name="receiver_phone" value="{{isset($sell) ? $sell->receiver_phone : old('receiver_phone')}}">
                        @error('receiver_phone')
                        <div id="" class="invalid-feedback">{{$message}}</div>
                        @enderror
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="validationServer01"> {{ __('home.phone') }} </label>
                        <input type="text" class="form-control @error('receiver_phone') {{'is-invalid'}} @enderror" id="receiver_phone" name="receiver_phone" value="{{isset($sell) ? $sell->receiver_phone : old('receiver_phone')}}">
                        @error('receiver_phone')
                        <div id="" class="invalid-feedback">{{$message}}</div>
                        @enderror
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="validationServer01"> {{ __('home.tazkira_no') }} </label>
                        <input type="text" class="form-control @error('tazkira_no') {{'is-invalid'}} @enderror" id="receiver_tazkira_no" name="receiver_tazkira_no" value="{{isset($sell) ? $sell->receiver_tazkira_no : old('receiver_tazkira_no')}}">
                        @error('receiver_tazkira_no')
                        <div id="" class="invalid-feedback">{{$message}}</div>
                        @enderror
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="validationServer01"> {{ __('home.zipcode') }} </label>
                        <input type="text" class="form-control @error('zipcode') {{'is-invalid'}} @enderror" id="zipcode" name="zipcode" value="{{isset($sell) ? $sell->zipcode : old('zipcode')}}">
                        @error('zipcode')
                        <div id="" class="invalid-feedback">{{$message}}</div>
                        @enderror
                    </div>
                    <div class="col-xl-3 mb-3">
                        <label for="validationServer04">{{ __('home.country') }}</label>
                        <select class="form-select form-control select2 @error('account_id') {{'is-invalid'}} @enderror" onchange="showData(this.value)" id="account_id" aria-describedby="validationServer04Feedback" required name="account_id">
                            <option> {{__('home.please_select')}}</option>
                            @foreach($accounts as $account)
                            <option value="{{$account->id}}" @if(isset($sell)) @if($sell->account_id == $account->id) selected = 'selected' @endif @endif >{{$account->name}}</option>
                            @endforeach
                        </select>
                        @error('account_id')
                        <div id="" class="invalid-feedback">{{$message}}</div>
                        @enderror
                    </div>
                    <div class="col-xl-6 mb-3">
                        <label for="validationServer01">{{ __('home.addre   ss') }}</label>
                        <input type="text" class="form-control @error('receiver_address') {{'is-invalid'}} @enderror" id="receiver_address" name="receiver_address" value="{{isset($sell) ? $sell->receiver_address : old('receiver_address')}}" autocomplete="off">
                        @error('receiver_address')
                        <div id="" class="invalid-feedback">{{$message}}</div>
                        @enderror
                    </div>


                </div>
            </fieldset>
            @if(!isset($sell))
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
                                    <th width="15%">{{__('home.stock')}}</th>
                                    <th width="15%">{{__('home.product')}}</th>
                                    {{-- <th width="15%">{{__('home.type')}}</th> --}}
                                    {{-- <th width="1%">{{__('home.currency')}}</th> --}}
                                    {{-- <th width="10%">{{__('home.sell_price')}}</th> --}}
                                    <th width="10%">{{__('home.quantity')}}</th>
                                    <th width="10%">{{__('home.sell')}}</th>
                                    <th width="11%">{{__('home.cbm')}}</th>
                                    <th width="11%">{{__('home.total')}}</th>
                                    <th width="5%">{{__('home.action')}}</th>
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
                <label class="col-md-2 {{App::getLocale() == 'en' ? 'offset-sm-7' : ''}} form-label text-right fw-bold">{{ __('home.grand_total') }}</label>
                <div class="col-md-3">
                    <input type="number" step="0.01" id="total" name="total" class="form-control" readonly="" value="{{isset($sell) ? $sell->total : old('total')}}">
                </div>
            </div>
            <div class=" row mb-4" dir="{{App::getLocale() == 'en' ? 'ltr' : 'rtl'}}">
                <label class="col-md-2 {{App::getLocale() == 'en' ? 'offset-sm-7' : ''}} form-label text-right fw-bold">{{ __('home.total_cbm') }}</label>
                <div class="col-md-3">
                    <input type="number" step="0.01" id="total_cbm" name="total_cbm" class="form-control" readonly="" value="{{isset($sell) ? $sell->total_cbm : old('total_cbm')}}">
                </div>
            </div>
            <div class=" row mb-4" dir="{{App::getLocale() == 'en' ? 'ltr' : 'rtl'}}">
                <label class="col-md-2 {{App::getLocale() == 'en' ? 'offset-sm-7' : ''}} form-label text-right fw-bold">{{ __('home.paid') }}</label>
                <div class="col-md-3">
                    <input type="number" step="0.01" id="paid" name="paid" class="form-control" value="{{isset($sell) ? $sell->paid : old('paid', 0) }}">
                </div>
            </div>
            <div class=" row mb-4" dir="{{App::getLocale() == 'en' ? 'ltr' : 'rtl'}}">
                <label class="col-md-2 {{App::getLocale() == 'en' ? 'offset-sm-7' : ''}} form-label text-right fw-bold">{{ __('home.balance') }}</label>
                <div class="col-md-3">
                    <input type="number" step="0.01" id="balance" name="balance" class="form-control" readonly="" value="{{isset($sell) ? $sell->balance : old('balance')}}">
                </div>
            </div>



            <div class="form-footer mt-2">
                <input type="submit" class="btn btn-primary" value="@if(isset($sell))
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
                '<select class="form-select form-control select2" onchange="loadProducts(this, ' + count + ')"  name="stocks[]" id="to_currency">' +
                "<option value=''>Select Stock</option>" + // Add this line for default selection
                +"@foreach ($stocks as $obj)" +
                "<option value='{{$obj->id}}' >" + '{{$obj->name}}' + "</option>" +
                "@endforeach" +
                "</select> " +
                "</td>"

            html += "<td>" +
                '<select class="form-select form-control select2 products' + count + '" onchange="CurrencyData(this.value, ' + count + ')"  name="product[]" id="to_currency">' +
                "<option value=''>Select Product</option>" + // Add this line for default selection
                "</select> " +
                "</td>"

            html += '<td class="d-none"><input type="hidden" step="0.01" name="original_purchase[]"id="original_purchase' + count + '"  oninput="calculate()" class="form-control" value="0.00" />';
            html += ' <input type="hidden" name="purchase[]" readonly id="purchases' + count + '" oninput="calculateSell(this)" class="form-control" value="0" /></td>';
            html += '<td><input type="number" name="quantity[]" oninput="calculate()" class="form-control" value="0" /></td>';
            html += '<td><input type="hidden" step="0.01" name="original_sell[]"id="original_sell' + count + '"  oninput="calculate()" class="form-control" value="0.00" />';
            html += ' <input type="number" step="0.01" name="cost[]"id="costs' + count + '"  oninput="calculate()" class="form-control" value="0.00" /></td>';

            html += '<td><input type="hidden" step="0.01" name="height[]"id="height' + count + '"  oninput="calculate()" class="form-control" value="0.00" /><input type="hidden" step="0.01" name="width[]"id="width' + count + '"  oninput="calculate()" class="form-control" value="0.00" /><input type="hidden" step="0.01" name="length[]"id="length' + count + '"  oninput="calculate()" class="form-control" value="0.00" /><input type="number" id="cbm' + count + '" name="cbm[]" readonly oninput="calculate()" class="form-control" value="0" /></td>';
            html += '<td><input type="number" name="total[]" readonly oninput="calculate()" class="form-control" value="0" /></td>';
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
            var grandTotalCBM = 0;
            $('#tbody tr').each(function() {
                var quantity = parseFloat($(this).find('[name="quantity[]"]').val()) || 0;
                var cost = parseFloat($(this).find('[name="cost[]"]').val()) || 0;
                var expense = parseFloat($(this).find('[name="expense[]"]').val()) || 0;
                var height = parseFloat($(this).find('[name="height[]"]').val()) || 0;
                var width = parseFloat($(this).find('[name="width[]"]').val()) || 0;
                var length = parseFloat($(this).find('[name="length[]"]').val()) || 0;

                var total = quantity * (cost + expense);
                var total_cbm = (height / 100) * (width / 100) * (length / 100);
                grandTotal += total;
                grandTotalCBM += total_cbm;

                // Update the total input field in the current row
                $(this).find('[name="total[]"]').val(total.toFixed(2));
                $(this).find('[name="cbm[]"]').val(total_cbm.toFixed(2));
            });

            // Update the grand_total input field
            paid = $('#paid').val();
            $('#total').val(grandTotal.toFixed(2));
            $('#balance').val((grandTotal - paid).toFixed(2));
            $('#total_cbm').val(grandTotalCBM.toFixed(2));

        });


    });

    function loadProducts(select, count) {
        var stockProductId = $(select).val();

        $.ajax({
            url: '/get-products/' + stockProductId,
            type: 'GET',
            success: function(response) {
                var products = response.products;
                var currency = response.currency;

                var options = "<option value=''>Select Product</option>";
                products.forEach(function(product) {
                    if (product.sub_products && product.sub_products.length > 0) {
                        // console.log('insdie if');
                        var totalAvailable = product.sub_products.reduce(function(acc, sub_products) {
                            return acc + parseFloat(sub_products.available);
                        }, 0);
                    } else {
                        var totalAvailable = 0;
                    }

                    options += "<option value='" + product.id + "' data-cost='" + (product.product.income_price + product.product.expense) + "' data-height='" + product.product.height + "' data-width='" + product.product.width + "' data-length='" + product.product.length + "'  data-original_purchase='" + (product.product.cost + product.product.expense) + "' data-original_sell='" + (product.product.sell ? product.product.sell : 0) + "' data-currency='" + (product.product.currency_id) + "' data-sell='" + (product.product.sell ? product.product.sell : 0) + "' data-available='" + totalAvailable + "' data-weight='" + product.product.weight + "'>" + product.product.name + " (Avail: " + totalAvailable + ")</option>";
                });
                $('.products' + count).html(options);
            },
            error: function(xhr, status, error) {
                // console.error(error);
            }
        });
    }

    function calculate() {
        var grandTotal = 0;
        var grandTotalCBM = 0;
        var balance = 0;

        $('#tbody tr').each(function() {
            var quantity = parseFloat($(this).find('[name="quantity[]"]').val()) || 0;
            var cost = parseFloat($(this).find('[name="cost[]"]').val()) || 0;
            var expense = parseFloat($(this).find('[name="expense[]"]').val()) || 0;

            var height = parseFloat($(this).find('[name="height[]"]').val()) || 0;
            var width = parseFloat($(this).find('[name="width[]"]').val()) || 0;
            var length = parseFloat($(this).find('[name="length[]"]').val()) || 0;

            var total = quantity * (cost + expense);
            // var total_cbm = height * width * length;
            var total_cbm = (height / 100) * (width / 100) * (length / 100);

            // console.log(total_cbm);
            total_cbm = parseFloat(total_cbm);
            // console.log(total_cbm);
            grandTotal += total;
            grandTotalCBM += quantity * total_cbm;

            // Update the total input field in the current row
            $(this).find('[name="total[]"]').val(total.toFixed(2));
            $(this).find('[name="cbm[]"]').val(total_cbm.toFixed(6));
        });

        grandTotalCBM = grandTotalCBM;
        // Update the grand_total input field
        $('#total').val(grandTotal.toFixed(2));
        $('#total_cbm').val(grandTotalCBM.toFixed(6));
        $('#balance').val(grandTotal.toFixed(2));
    }

    $('#paid').keyup(function() {

        var total = parseFloat($('#total').val());
        var paid = parseFloat($('#paid').val());

        $('#balance').val(total - paid);
    });

    $('#client_id').change(function() {
        if ($('#client_id').find(":selected").val() == 'new') {
            $('#clientForm').modal('show');
            // RELOAD DROPDOWN WITH NEW CLIENT NAME
        }
    });

    // $('#product').change(function() {
    // 	var stock = $(this).find(':selected').data('cost');

    // });

    function calculateSell(select) {
        // Get the selected option
        var selectedOption = $(select).find('option:selected');
        var type = $(selectedOption).val();


        // Get the data-sell value
        var rate = $('#rate').val();
        var sellValue = parseFloat(selectedOption.data('sell') || 0);
        var costValue = parseFloat(selectedOption.data('cost') || 0);

        var original_sell = selectedOption.data('original_sell') || 0;
        var original_purchase = selectedOption.data('original_purchase') || 0;
        var to_currency_id = selectedOption.data('currency');

        var currency_id = $('#currency_id').val();

        var width = selectedOption.data('width') || 0;
        var height = selectedOption.data('height') || 0;
        var length = selectedOption.data('length') || 0;

        // Set the data-sell value to the corresponding sell[] input field in the current row
        $(select).closest('tr').find('input[name="purchase[]"]').val(costValue);

        $(select).closest('tr').find('input[name="height[]"]').val(height);
        $(select).closest('tr').find('input[name="width[]"]').val(width);
        $(select).closest('tr').find('input[name="length[]"]').val(length);
        $(select).closest('tr').find('input[name="cost[]"]').val(sellValue);
        $(select).closest('tr').find('input[name="original_sell[]"]').val(original_sell);
        $(select).closest('tr').find('input[name="original_purchase[]"]').val(original_purchase);
        $(select).closest('tr').find('input[name="to_currency_id[]"]').val(to_currency_id);
        // $('.to_currency_id' + count).val(product.product.currency_id);
        // console.log(sellValue);
    }

    function showData(value) {
        $.ajax({
            url: '/get-client-data/' + value,
            type: 'GET',
            success: function(response) {
                var data = response.data;
                // console.log('dd', data)

                var options = "<option value=''>Select Account</option>";
                data.forEach(function(account) {
                    options += "<option value='" + account.id + "'>" + account.name + " (" + account.amount + '-' + account.currency.name + ")</option>";
                    console.log('ss', response.client.client.type)
                    if (response.client.client.type == 'walkin') {
                        $(".my_div").css('display', "block")
                    } else {
                        $(".my_div").css('display', "none")

                    }
                });
                $('#account_id').html(options);
                $('#rate').val(0);
                $('#operation').val('');
            },
            error: function(xhr, status, error) {
                console.error(error);

            }
        });



    }

    function showCurrency(value) {
        $.ajax({
            url: '/get_latest_exchange_rate/' + value,
            type: 'GET',
            success: function(response) {
                var data = response.rate;
                // console.log(data.rate);
                // console.log(data.operation);
                $('#rate').val(data.rate);
                $('#operation').val(data.operation);
            },
            error: function(xhr, status, error) {
                // console.error(error);
            }
        });
    }


    function CurrencyData(select, count) {
        var rate = 1;
        var action = 'multiply';
        $.ajax({
            url: '/get-product-currency/' + select,
            type: 'GET',
            success: function(response) {
                var data = response.data;
                $('#cbm' + count).val((data.product.height / 100) * (data.product.width / 100) * (data.product.length / 100));
                rate = parseFloat($('#rate').val());
                action = $('#operation').val();
                if (action == 'multiply') {
                    $('#costs' + count).val(data.product.sell_price / rate);
                } else {
                    $('#costs' + count).val(data.product.sell_price * rate);
                }
            },
            error: function(xhr, status, error) {
                // console.error(error);
            }
        });


    }

    $(document).on('change', 'select.select2', function() {
        // Call calculateSell when product[] dropdown changes
        calculateSell(this);
    });
    // $(document).on('change', '.change_type', function() {

    //     // Call calculateSell when product[] dropdown changes
    //     calculateSell(this);
    // });

    $(document).ready(function() {
        // Call calculateSell for each select2 dropdown on document ready
        // showCurrency($("#account_id").find('option:selected').val());
        $('select.select2').each(function() {
            calculateSell(this);
        });

    });
</script>
<div class="modal fade " id="clientForm">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title">{{ __('home.new_client') }}</h6><button aria-label="Close" class="btn-close" data-bs-dismiss="modal"><span aria-hidden="true">&times;</span></button>
            </div>
            <form action="{{ route('client.store') }}" method="POST" class="d-inline">
                @method('POST')
                @csrf
                <div class="modal-body">
                    <div class="form-row mb-3">

                        <div class="col-xl-4 col-sm-4 mb-3">
                            <label for="validationServer01">{{ __('home.name') }}</label>
                            <input type="text" class="form-control @error('name') {{'is-invalid'}} @enderror" id="name" name="name" value="">
                            <input type="hidden" class="form-control" name="from_sell" value="1">
                            <input type="hidden" class="form-control" name="amount" value="0">
                            @error('name')
                            <div id="" class="invalid-feedback">{{$message}}</div>
                            @enderror
                        </div>
                        <div class="col-xl-4 col-sm-4 mb-3">
                            <label for="validationServer01">{{ __('home.currency') }}</label>
                            <select class="form-select form-control select2 @error('client_id') {{'is-invalid'}} @enderror" id="treasury" name="treasury">
                                <option> {{__('home.please_select')}}</option>

                                @foreach($currencies as $obj)

                                <option value="{{$obj->id}}">{{$obj->name}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-xl-4 col-sm-4 mb-3">
                            <label for="validationServer01">{{ __('home.mobile') }}</label>
                            <input type="text" class="form-control @error('mobile') {{'is-invalid'}} @enderror" id="mobile" name="mobile" value="">
                            @error('mobile')
                            <div id="" class="invalid-feedback">{{$message}}</div>
                            @enderror
                        </div>
                        <div class="col-xl-4 col-sm-4 mb-3">
                            <label for="validationServer01">{{ __('home.tazkira_no') }}</label>
                            <input type="text" class="form-control @error('nid') {{'is-invalid'}} @enderror" id="nid" name="nid" value="">
                            @error('nid')
                            <div id="" class="invalid-feedback">{{$message}}</div>
                            @enderror
                        </div>

                        <div class="col-xl-12 col-sm-12 mb-3">
                            <label for="validationServer01">{{ __('home.address') }}</label>
                            <input type="text" class="form-control @error('address') {{'is-invalid'}} @enderror" id="address" name="address" value="">
                            @error('address')
                            <div id="" class="invalid-feedback">{{$message}}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('home.cancel') }}</button>
                    <button type="submit" class="btn btn-primary">{{ __('home.save') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

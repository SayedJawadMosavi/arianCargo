@extends('layouts.app')

@section('title', 'New Cargo')
@section('content')

<div class="card mt-4">

    @if (session()->has('success') || session()->has('error') )
    @include('layouts.partials.components.alert')
    @endif

    <div class="card-header d-flex justify-content-between">
        <h4 class="card-title">
            @if(isset($cargo))
            {{ __('home.edit_sell') }}
            @else
            {{ __('home.new_sell') }}
            @endif
        </h4>
        <a href="{{route('cargo.index')}}" class="btn btn-primary">{{ __('home.all_sells') }}</a>

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
        <form action="{{isset($cargo) ? route('cargo.update', $cargo) : route('cargo.store')}}" id="dynamicForm" method="POST" enctype="multipart/form-data">
            @csrf
            @if(isset($cargo))
            @method('PUT')
            @else
            @method('POST')
            @endif

            <div class="form-row mb-3">
                <div class="col-xl-4 mb-3">
                    <label for="validationServer04">{{ __('home.sender') }}</label>
                    <select class="form-select form-control select2 @error('client_id') {{'is-invalid'}} @enderror" id="client_id" aria-describedby="validationServer04Feedback" required name="client_id">
                        <option> {{__('home.please_select')}}</option>
                        <option value="new"> {{__('home.new_customer')}}</option>
                        @foreach($clients as $client)

                        <option value="{{$client->id}}" @if(isset($cargo)) @if($cargo->client_id == $client->id) selected = 'selected' @endif @endif >{{$client->name}}</option>
                        @endforeach
                    </select>
                    @error('client_id')
                    <div id="" class="invalid-feedback">{{$message}}</div>
                    @enderror
                </div>
                <div class="col-xl-4 mb-3">
                    <label for="validationServer04">{{ __('home.receiver') }}</label>
                    <select class="form-select form-control select2 @error('receiver_id') {{'is-invalid'}} @enderror" id="receiver_id" aria-describedby="validationServer04Feedback" required name="receiver_id">
                        <option> {{__('home.please_select')}}</option>
                        <option value="new"> {{__('home.new_customer')}}</option>
                        @foreach($clients as $client)

                        <option value="{{$client->id}}" @if(isset($cargo)) @if($cargo->receiver_id == $client->id) selected = 'selected' @endif @endif >{{$client->name}}</option>
                        @endforeach
                    </select>
                    @error('receiver_id')
                    <div id="" class="invalid-feedback">{{$message}}</div>
                    @enderror
                </div>




                @if ($settings->date_type=='shamsi')

                <div class="col-xl-3 mb-3">
                    <label for="validationServer01">{{ __('home.date') }}</label>
                    <input type="text" class="form-control form-control @error('date') {{'is-invalid'}} @enderror" name="shamsi_date" autocomplete="off" id="dates" value="{{isset($cargo) ? $cargo->shamsi_date : old('date')}}">
                    @error('date')
                    <div id="" class="invalid-feedback">{{$message}}</div>
                    @enderror
                </div>
                @else
                <div class="col-xl-3 mb-3">
                    <label for="validationServer01">{{ __('home.date') }}</label>
                    <input type="date" class="form-control @error('date') {{'is-invalid'}} @enderror" id="date" name="miladi_date" value="{{ isset($cargo) ? $cargo->miladi_date : date('Y-m-d') }}">
                    @error('date')
                    <div id="" class="invalid-feedback">{{$message}}</div>
                    @enderror
                </div>
                @endif
                <div class="col-xl-2 mb-3">
                    <label for="validationServer01">{{ __('home.bill') }}</label>
                    <input type="text" class="form-control form-control @error('bill') {{'is-invalid'}} @enderror" name="bill" autocomplete="off" id="bill" value="{{isset($cargo) ? $cargo->bill : old('bill')}}">
                    @error('bill')
                    <div id="" class="invalid-feedback">{{$message}}</div>
                    @enderror
                </div>
                <div class="col-xl-2 mb-3">
                    <label for="validationServer01">{{ __('home.sn') }}</label>
                    <input type="text" class="form-control form-control @error('bill') {{'is-invalid'}} @enderror" name="number" autocomplete="off" id="number" value="{{isset($cargo) ? $cargo->number : old('number')}}">
                    @error('bill')
                    <div id="" class="invalid-feedback">{{$message}}</div>
                    @enderror
                </div>
                <div class="col-xl-7 mb-3">
                    <label for="validationServer01">{{ __('home.description') }}</label>
                    <input type="text" class="form-control @error('description') {{'is-invalid'}} @enderror" id="description" name="description" value="{{isset($cargo) ? $cargo->description : old('description')}}" autocomplete="off">
                    @error('description')
                    <div id="" class="invalid-feedback">{{$message}}</div>
                    @enderror
                </div>

            </div>

            @if(!isset($cargo))
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
                                    <th width="15%">{{__('home.items')}}</th>

                                    <th width="10%">{{__('home.quantity')}}</th>
                                    <th width="15%">{{__('home.cbm')}}</th>

                                    <th width="11%">{{__('home.type')}}</th>
                                    <th width="11%">{{__('home.value')}}</th>
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
            <div class="row mb-4" dir="{{ App::getLocale() == 'en' ? 'ltr' : 'rtl' }}">
                <div class="col-md-2">
                    <label for="total" class="form-label fw-bold text-right d-block">{{ __('home.total_weight') }}</label>
                    <input type="number" step="0.01" id="total_weight" name="total_weight" class="form-control" value="{{ isset($cargo) ? $cargo->total_weight : old('total_weight') }}">
                </div>

                <div class="col-md-2">
                    <label for="per_weight" class="form-label fw-bold text-right d-block">{{ __('home.per_weight') }}</label>
                    <input type="number" step="0.01" id="per_weight" name="per_weight" class="form-control" value="{{ isset($cargo) ? $cargo->per_weight : old('per_weight') }}">
                </div>

                <div class="col-md-2">
                    <label for="total" class="form-label fw-bold text-right d-block">{{ __('home.grand_total') }}</label>
                    <input type="number" step="0.01" id="total" readonly name="total" class="form-control" value="{{ isset($cargo) ? $cargo->total : old('total') }}">
                </div>
                <div class="col-xl-2 mb-3">
                    <label for="validationServer04">{{ __('home.account') }}</label>
                    <select class="form-select form-control select2 @error('account_id') {{'is-invalid'}} @enderror" onchange="showData(this.value)" id="account_id" aria-describedby="validationServer04Feedback" required name="account_id">
                        <option value="0"> {{__('home.please_select')}}</option>
                        @foreach($accounts as $account)
                        <option value="{{$account->id}}" @if(isset($cargo)) @if($cargo->account_id == $account->id) selected = 'selected' @endif @endif >{{$account->name}}</option>
                        @endforeach
                    </select>
                    @error('account_id')
                    <div id="" class="invalid-feedback">{{$message}}</div>
                    @enderror
                </div>
                <div class="col-md-2">
                    <label for="paid" class="form-label fw-bold text-right d-block">{{ __('home.paid') }}</label>
                    <input type="number" step="0.01" id="paid" name="paid" class="form-control" value="{{ isset($cargo) ? $cargo->paid : old('paid', 0) }}">
                </div>
                <div class="col-md-2">
                    <label for="balance" class="form-label fw-bold text-right d-block">{{ __('home.balance') }}</label>
                    <input type="number" step="0.01" id="balance" name="balance" class="form-control" readonly value="{{ isset($cargo) ? $cargo->balance : old('balance') }}">
                </div>

            </div>




            <div class="form-footer mt-2">
                <input type="submit" class="btn btn-primary" value="@if(isset($cargo))
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
            html += '<td class=""><input type="text"  name="item_name[]"id="item_name' + count + '" class="form-control" value="" />';


            html += '<td><input type="number" name="quantity[]"oninput="validateQuantity(this)";  class="form-control" value="0" /></td>';
            html += '<td><input type="text" name="cbm[]"  class="form-control"  /></td>';
            html += '<td><input type="text" name="type[]"  class="form-control"  /></td>';
            html += '<td><input type="text" name="values[]"  class="form-control"  /></td>';



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



        });


    });

    document.getElementById('dynamicForm').addEventListener('submit', function(e) {

        let valid = true;
        document.querySelectorAll('input[name="item_name[]"],input[name="quantity[]"], input[name="cbm[]"],input[name="type[]"], input[name="values[]"]').forEach(function(input) {
            if (!validateQuantity(input)) {
                valid = false;
            }
        });

        if (!valid) {
            e.preventDefault();
            alert('Please fill in all required fields.');
        }
    });
    function validateQuantity(input) {
        if (input.value === '') {
            input.style.border = '1px solid red';
            return false;
        } else {
            input.style.border = '';
            return true;
        }
    }
    $('#total_weight, #per_weight').on('keyup change', function() {
        var weight = parseFloat($('#total_weight').val()) || 0;
        var perWeight = parseFloat($('#per_weight').val()) || 0;
        var total = weight * perWeight;

        $('#total').val(total.toFixed(2));
    });


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
    $('#receiver_id').change(function() {
        if ($('#receiver_id').find(":selected").val() == 'new') {
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
                        <div class="col-md-3 mb-3">
                            <label for="validationServer01"> {{ __('home.zipcode') }} </label>
                            <input type="text" class="form-control @error('zipcode') {{'is-invalid'}} @enderror" id="zipcode" name="zipcode" value="{{isset($cargo) ? $cargo->zipcode : old('zipcode')}}">
                            @error('zipcode')
                            <div id="" class="invalid-feedback">{{$message}}</div>
                            @enderror
                        </div>
                        <div class="col-xl-3 mb-3">
                            <label for="validationServer04">{{ __('home.country') }}</label>
                            <select class="form-control " name="country" id="country">

                                <option value="">{{ __('home.select') }}</option>

                                <option value="1">afghanistan</option>
                                <option value="2">United States</option>
                            </select>
                            @error('country')
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

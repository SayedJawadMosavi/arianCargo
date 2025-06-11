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
                    {{-- <select class="form-select form-control select2 @error('client_id') {{'is-invalid'}} @enderror" id="client_id" aria-describedby="validationServer04Feedback" required name="client_id">
                    <option> {{__('home.please_select')}}</option>
                    <option value="new"> {{__('home.new_customer')}}</option>
                    @foreach($clients as $client)

                    <option value="{{$client->id}}" @if(isset($cargo)) @if($cargo->client_id == $client->id) selected = 'selected' @endif @endif >{{$client->name}}</option>
                    @endforeach
                </select>--}}
                <select id="client" name="client_id" class="form-control" style="width: 100%">
                        @if(isset($cargo))
                        @foreach($clients as $client)
                        <option value="{{$client->id}}" selected @if(isset($cargo)) @if($cargo->client_id == $client->id) selected = 'selected' @endif @endif >{{$client->name}}</option>
                        @endforeach
                        @endif
                    </select>
                    @error('client_id')
                    <div id="" class="invalid-feedback">{{$message}}</div>
                    @enderror
                </div>
                <div class="col-xl-4 mb-3">
                    <label for="validationServer04">{{ __('home.receiver') }}</label>
                    {{--<select class="form-select form-control select2 @error('receiver_id') {{'is-invalid'}} @enderror" id="receiver_id" aria-describedby="validationServer04Feedback" required name="receiver_id">
                        <option> {{__('home.please_select')}}</option>
                        <option value="new"> {{__('home.new_customer')}}</option>
                        @foreach($clients as $client)

                        <option value="{{$client->id}}" @if(isset($cargo)) @if($cargo->receiver_id == $client->id) selected = 'selected' @endif @endif >{{$client->name}}</option>
                        @endforeach
                    </select>--}}
                    <select id="receiver" name="receiver_id" class="form-control" style="width: 100%">
                        @if(isset($cargo))
                        @foreach($clients as $client)
                        <option value="{{$client->id}}" @if(isset($cargo)) @if($cargo->receiver_id == $client->id) selected = 'selected' @endif @endif >{{$client->name}}</option>
                        @endforeach

                        @endif
                    </select>
                    @error('receiver_id')
                    <div id="" class="invalid-feedback">{{$message}}</div>
                    @enderror
                </div>


                <div class="col-xl-4 mb-3">
                    <label for="validationServer01">{{ __('home.country') }}</label>
                   <select class="form-select select2" name="country_id" id="country" onchange="setRateToPerWeight(this)" required>
                        <option selected disabled value="">{{ __('home.please_select') }}</option>
                        @foreach($countries as $country)
                            <option value="{{ $country->id }}" data-rate="{{ $country->rate }}" @if(isset($cargo)) @if($cargo->country_id == $country->id) selected = 'selected' @endif @endif >
                                {{ $country->name }}
                            </option>
                        @endforeach
                    </select>

                    @error('country_id')
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
                    <input type="text" class="form-control form-control @error('bill') {{'is-invalid'}} @enderror" name="bill" readonly autocomplete="off" id="bill" value="{{$nextBillNumber}}">
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
                <div class="col-xl-5 mb-3">
                    <label for="validationServer01">{{ __('home.description') }}</label>
                    <input type="text" class="form-control @error('description') {{'is-invalid'}} @enderror" id="description" name="description" value="{{isset($cargo) ? $cargo->description : old('description')}}" autocomplete="off">
                    @error('description')
                    <div id="" class="invalid-feedback">{{$message}}</div>
                    @enderror
                </div>

            </div>

            @if(!isset($cargo))

        <div class="bg-white p-3  shadow-sm mb-4 border-start border-1">
            <h5 class="text-danger mb-3"><i class="fas fa-boxes me-1"></i> {{ __('home.products') }}</h5>
            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead class="table-danger">
                        <tr>
                            <th>{{ __('home.items') }}</th>
                            <th>{{ __('home.quantity') }}</th>
                            <th>{{ __('home.cbm') }}</th>
                            <th>{{ __('home.type') }}</th>
                            <th>{{ __('home.value') }}</th>
                            <th>{{ __('home.action') }}</th>
                        </tr>
                    </thead>
                    <tbody id="tbody"></tbody>
                </table>
            </div>
        </div>
            @endif
            <div class="row mb-4" dir="{{ App::getLocale() == 'en' ? 'ltr' : 'rtl' }}">
                <div class="col-md-4">
                    <label for="total" class="form-label fw-bold text-right d-block">{{ __('home.total_weight') }}</label>
                    <input type="number" step="0.01" id="total_weight" name="total_weight" class="form-control" value="{{ isset($cargo) ? $cargo->total_weight : old('total_weight') }}">
                </div>


                <input type="hidden"readonly step="0.01" id="per_weight" name="per_weight" class="form-control" value="{{ isset($cargo) ? $cargo->per_weight : old('per_weight') }}">
                <input type="hidden"readonly step="0.01" id="operations" name="operation" class="form-control" value="{{ isset($cargo) ? $cargo->operation : old('operation') }}">

                <div class="col-md-4">
                    <label for="per_weight"  class="form-label fw-bold text-right d-block">{{ __('home.per_weight') }}</label>
                    <input type="number" step="0.01" id="per_pay_cost" name="per_pay_cost" class="form-control" value="{{ isset($cargo) ? $cargo->new_per_weight : old('new_per_weight') }}">

                </div>

                <input type="hidden" step="0.01" id="total" readonly name="total" class="form-control" value="{{ isset($cargo) ? $cargo->total : old('total') }}">


                <div class="col-md-3" id="new_total_dev">
                    <label for="total" class="form-label fw-bold text-right d-block">{{ __('home.new_total') }}</label>
                    <input type="number" step="0.01" id="new_total" readonly name="new_total" class="form-control" value="{{ isset($cargo) ? $cargo->new_total : old('new_total') }}">
                </div>
                <div class="col-md-4" style="display: none;" id="per_weight_div">
                    <label for="per_weight_equalent"  class="form-label fw-bold text-right d-block">Equalent Per Weight</label>
                    <input type="number" step="0.01" id="per_weight_equalent" name="per_weight_equalent" class="form-control" value="{{ isset($cargo) ? $cargo->per_weight_equalent : old('per_weight_equalent') }}">

                </div>
                 <div class="col-xl-4 mb-3" id="exchange_rate_div" style="display: none;">
                    <label for="validationServer01">{{ __('home.rate') }}</label>
                    <input type="number" step="0.01" class="form-control " id="rates" name="rate" value="{{isset($cargo) ? $cargo->rate : old('rate')}}" >
                    @error('rate')
                    <div id="" class="invalid-feedback">{{$message}}</div>
                    @enderror
                </div>
                <div class="col-md-3" style="display: none;" id="total_div">
                    <label for="total_equalent" class="form-label fw-bold text-right d-block">Equalent Total</label>
                    <input type="number" step="0.01" id="total_equalent" readonly name="total_equalent" class="form-control" value="{{ isset($cargo) ? $cargo->total_equalent : old('total_equalent') }}">
                </div>
                <div class="col-xl-4 mb-3">
                    <label for="validationServer04">{{ __('home.account') }}</label>
                    <select class="form-select form-control select2 @error('account_id') {{'is-invalid'}} @enderror"  id="account_id" aria-describedby="validationServer04Feedback" required name="account_id">
                        <option value="0"> {{__('home.please_select')}}</option>
                        @foreach($accounts as $account)
                        <option value="{{$account->id}}" @if(isset($cargo)) @if($cargo->account_id == $account->id) selected = 'selected' @endif @endif >{{$account->name}}</option>
                        @endforeach
                    </select>
                    @error('account_id')
                    <div id="" class="invalid-feedback">{{$message}}</div>
                    @enderror
                </div>
                <div class="col-md-3" id="paid_div">
                    <label for="paid" class="form-label fw-bold text-right d-block">{{ __('home.paid') }}</label>
                    <input type="number" step="0.01" id="paid" name="paid" class="form-control" value="{{ isset($cargo) ? $cargo->paid : old('paid', 0) }}">
                </div>
                <div class="col-md-3" id="paid_div_equalent" style="display: none;">
                    <label for="paid" class="form-label fw-bold text-right d-block">{{ __('home.paid') }}</label>
                    <input type="number" step="0.01" id="paid_equalent" name="paid_equalent" class="form-control" value="{{ isset($cargo) ? $cargo->paid_equalent : old('paid_equalent', 0) }}">
                </div>
                <input type="hidden" step="0.01" id="balance" name="balance" class="form-control" readonly value="{{ isset($cargo) ? $cargo->balance : old('balance') }}">
                <div class="col-md-3" id="balance_div">
                    <label for="balance" class="form-label fw-bold text-right d-block">{{ __('home.balance') }}</label>
                    <input type="number" step="0.01" id="new_balance" name="new_balance" class="form-control" readonly value="{{ isset($cargo) ? $cargo->new_balance : old('new_balance') }}">
                </div>
                <div class="col-md-3" id="equalent_balance_dev" style="display: none;">
                    <label for="balance" class="form-label fw-bold text-right d-block">Equalent Balance</label>
                    <input type="number" step="0.01" id="equalent_balance" name="equalent_balance" class="form-control" readonly value="{{ isset($cargo) ? $cargo->equalent_balance : old('equalent_balance') }}">
                </div>

                <!-- Optional debug fields -->

                <input type="hidden" name="exchange_type" id="exchange_type" readonly>
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
<div class="modal fade" id="clientForm" tabindex="-1" aria-labelledby="clientFormLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content rounded-4 shadow-lg border-0">

            {{-- Header --}}
            <div class="modal-header bg-primary text-white rounded-top">
                <h5 class="modal-title" id="clientFormLabel">
                    <i class="fas fa-user-plus me-2"></i> {{ __('home.new_client') }}
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            {{-- Body --}}
            <div class="modal-body bg-light">
                <div class="row g-3">

                    <div class="col-md-4">
                        <label class="form-label fw-bold text-dark">
                            <i class="fa fa-user me-1 text-primary"></i> {{ __('home.name') }}
                        </label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="">
                        <input type="hidden" name="from_sell" value="1">
                        <input type="hidden" name="amount" value="0">
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-bold text-dark">
                            <i class="fa fa-phone me-1 text-success"></i> {{ __('home.mobile') }}
                        </label>
                        <input type="text" class="form-control @error('mobile') is-invalid @enderror" id="mobile" name="mobile" value="">
                        @error('mobile') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-bold text-dark">
                            <i class="fa fa-id-card me-1 text-info"></i> {{ __('home.tazkira_no') }}
                        </label>
                        <input type="text" class="form-control @error('nid') is-invalid @enderror" id="nid" name="nid" value="">
                        @error('nid') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-bold text-dark">
                            <i class="fa fa-mail me-1 text-warning"></i> {{ __('home.zipcode') }}
                        </label>
                        <input type="text" class="form-control @error('zipcode') is-invalid @enderror" id="zipcode" name="zipcode"
                            value="{{ isset($cargo) ? $cargo->zipcode : old('zipcode') }}">
                        @error('zipcode') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-bold text-dark">
                            <i class="fa fa-flag me-1 text-danger"></i> {{ __('home.country') }}
                        </label>
                        <select class="form-select @error('country_id') is-invalid @enderror" name="country" id="country_id" required>
                            <option selected disabled value="">{{ __('home.please_select') }}</option>
                            @foreach($countries as $country)
                                <option value="{{ $country->id }}">{{ $country->name }}</option>
                            @endforeach
                        </select>
                        @error('country_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-6 col-sm-4">
                        <label class="" for="inlineFormInputGroup">Permanent</label>
                        <select class="form-control " name="permanent" id="permanent">
                            <option value="no">{{__('home.no')}}</option>
                            <option value="yes">{{__('home.yes')}}</option>
                        </select>
                        @error('permanent')
                        <span class="alert text-danger">{{$message}}</span>
                        @enderror
                    </div>
                    <div class="col-md-12">
                        <label class="form-label fw-bold text-dark">
                            <i class="fa fa-map me-1 text-secondary"></i> {{ __('home.address') }}
                        </label>
                        <input type="text" class="form-control @error('address') is-invalid @enderror" id="address" name="address" value="">
                        @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <input type="hidden" name="client_type" id="client_type">

                </div>
            </div>

            {{-- Footer --}}
            <div class="modal-footer bg-white">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <i class="fa fa-times me-1"></i> {{ __('home.cancel') }}
                </button>
                <button type="button" id="ajaxSubmit" class="btn btn-primary">
                    <i class="fa fa-save me-1"></i> {{ __('home.save') }}
                </button>
            </div>
        </div>
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
document.addEventListener('DOMContentLoaded', function () {
    const selectedAccount = "{{ request('accounts') }}";
    const selectedBankAccount = "{{ request('bank_accounts') }}";

    // پر کردن فیلد های اکانت و بانک اکانت (اگر لازم داری با AJAX بیاری از سرور)

    if (selectedAccount) {
        setTimeout(() => {
            document.getElementById("accounts").value = selectedAccount;
        }, 300);
    }

    if (selectedBankAccount) {
        setTimeout(() => {
            document.getElementById("bank_accounts").value = selectedBankAccount;
        }, 300);
    }

    // نمایش بخش مربوط به نوع حساب انتخابی
    const accountType = "{{ request('account_type') }}";
    if (accountType === "client_account") {
        document.getElementById("account-wrapper").style.display = "block";
    }
    if (accountType === "bank_account") {
        document.getElementById("bank_account-wrapper").style.display = "block";
    }
});
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {

        var element = document.getElementById('country_id');
        var choices = new Choices(element, {
            searchEnabled: true,
            removeItemButton: true,
        });

    });
</script>
<script>

    $(document).ready(function () {
            // Add a short delay to ensure #client is in the DOM
            setTimeout(function () {
                if ($('#client').length === 0) {
                    console.warn('#client not found in DOM');
                    return;
                }

                console.log('#client found, initializing Select2');

                $('#client').select2({
                    placeholder: '{{ __("home.select") }}',
                    minimumInputLength: 1,
                    ajax: {
                        url: '/clients/select2',
                        dataType: 'json',
                        delay: 1000,
                        data: function (params) {
                            return {
                                q: params.term
                            };
                        },
                        processResults: function (data) {
                            let results = data.map(client => ({
                                id: client.id,
                                text: client.text
                            }));

                            results.unshift({
                                id: 'new',
                                text: '{{ __("home.new_customer") }}',
                                isNew: true
                            });

                            return { results };
                        }
                    }
                });

                // "New client" handler
                $('#client').on('select2:select', function (e) {
                    const data = e.params.data;
                    if (data.id === 'new') {
                    $('#client_type').val('sender');

                        $('#clientForm').modal('show');
                        $('#client').val(null).trigger('change');
                    }
                });

                // Preselect client if editing
                @if(isset($cargo) && $cargo->client)
                    let selectedClient = {
                        id: {{ $cargo->client->id }},
                        text: "{{  $cargo->client->name }} - {{ $cargo->client->mobile }}"
                    };
                    let select = $('#client');
                    let option = new Option(selectedClient.text, selectedClient.id, true, true);
                    select.append(option).trigger('change');
                @endif
            }, 100); // delay of 100ms
        });


        // @if(isset($cargo) && $cargo->client)
        //     var selectedClient = {
        //         id: {{ $cargo->client->id }},
        //         text: "{{ $cargo->client->type == 'corporate' ? $cargo->client->company : $cargo->client->name }} - {{ $cargo->client->mobile }}"
        //     };

        //     let select = $('#client');
        //     let option = new Option(selectedClient.text, selectedClient.id, true, true);
        //     select.append(option).trigger('change');
        // @endif

$(document).ready(function () {
    setTimeout(function () {
        $('#receiver').select2({
            placeholder: '{{ __("home.select") }}',
            minimumInputLength: 1,
            ajax: {
                url: '/clients/select2',
                dataType: 'json',
                delay: 800,
                data: function (params) {
                    return {
                        q: params.term
                    };
                },
                processResults: function (data) {
                    let results = data.map(client => ({
                        id: client.id,
                        text: client.text
                    }));
                    results.unshift({
                        id: 'new',
                        text: '{{ __("home.new_customer") }}',
                        isNew: true
                    });
                    return { results };
                }
            }
        });

        $('#receiver').on('select2:select', function (e) {
            const data = e.params.data;
            if (data.id === 'new') {
                $('#client_type').val('receiver');

                $('#clientForm').modal('show');
                $('#receiver').val(null).trigger('change');
            }
        });
    }, 100); // 100ms delay
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
    $('#total_weight, #rates,#per_pay_cost,#new_total').on('keyup change', function() {
        var weight = parseFloat($('#total_weight').val()) || 0;
        var perWeight = parseFloat($('#per_weight').val()) || 0;
        var rate = parseFloat($('#rates').val()) || 0;
        var perPayWeight = parseFloat($('#per_pay_cost').val()) || 0;
        var per_weight_equalent = parseFloat($('#per_weight_equalent').val()) || 0;
        var total = weight * perWeight;
        var new_total = weight * perPayWeight;
        var total_equalent = weight * per_weight_equalent;

        $('#total').val(total.toFixed(2));
        $('#new_total').val(new_total.toFixed(2));
        $('#per_weight_equalent').val(perPayWeight * rate);
        $('#total_equalent').val(weight * $('#per_weight_equalent').val());
        $('#equalent_balance').val($('#total_equalent').val() - $('#paid_equalent').val());
        $('#new_balance').val($('#new_total').val() - $('#paid').val());

    });


    $('#paid,  #paid_equalent').keyup(function() {

        var total = parseFloat($('#total').val());
        var new_total = parseFloat($('#new_total').val());
        var total_equalent = parseFloat($('#total_equalent').val());
        var paid = parseFloat($('#paid').val());
        var paid_equalent = parseFloat($('#paid_equalent').val());

        $('#balance').val(total - paid);
        $('#new_balance').val(new_total - paid);
        $('#equalent_balance').val(total_equalent-paid_equalent);

    });

    $('#client_id').change(function() {
        if ($('#client_id').find(":selected").val() == 'new') {
        $('#client_type').val('sender');

            $('#clientForm').modal('show');
            // RELOAD DROPDOWN WITH NEW CLIENT NAME
        }
    });
    $('#receiver_id').change(function() {
        if ($('#receiver_id').find(":selected").val() == 'new') {
        $('#client_type').val('receiver');

            $('#clientForm').modal('show');
            // RELOAD DROPDOWN WITH NEW CLIENT NAME
        }
    });

    // $('#product').change(function() {
    // 	var stock = $(this).find(':selected').data('cost');

    // });
    function setRateToPerWeight(select) {
        var selectedOption = select.options[select.selectedIndex];
        var rate = selectedOption.getAttribute('data-rate');
        document.getElementById('per_weight').value = rate || '';
        document.getElementById('per_pay_cost').value = rate || '';
    }
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

    // function showData(value) {

    //     $.ajax({
    //         url: '/get_latest_exchange_rate/' + value,
    //         type: 'GET',
    //         success: function(response) {
    //             var data = response.rate;
    //             $('#rate').val(data.rate);
    //             $('#exchange_type').val(data.operation);
    //         },
    //         error: function(xhr, status, error) {
    //             // console.error(error);
    //         }
    //     });
    //     // $.ajax({
    //     //     url: '/get-client-data/' + value,
    //     //     type: 'GET',
    //     //     success: function(response) {
    //     //         var data = response.data;
    //     //         // console.log('dd', data)

    //     //         var options = "<option value=''>Select Account</option>";
    //     //         data.forEach(function(account) {
    //     //             options += "<option value='" + account.id + "'>" + account.name + " (" + account.amount + '-' + account.currency.name + ")</option>";
    //     //             console.log('ss', response.client.client.type)
    //     //             if (response.client.client.type == 'walkin') {
    //     //                 $(".my_div").css('display', "block")
    //     //             } else {
    //     //                 $(".my_div").css('display', "none")

    //     //             }
    //     //         });
    //     //         $('#account_id').html(options);
    //     //         $('#rate').val(0);
    //     //         $('#operation').val('');
    //     //     },
    //     //     error: function(xhr, status, error) {
    //     //         console.error(error);

    //     //     }
    //     });



    // }

$('#account_id').on('change', function () {
    var accountId = $(this).val();
    console.log('aaa',accountId)

    // Fetch exchange rate if needed
    $.ajax({
        url: '/get_latest_exchange_rate/' + accountId,
        type: 'GET',
        success: function (response) {
            var data = response.rate;

            let rate = parseFloat(data.rate);
            let operation = data.operation;

            let TotalWeight = parseFloat($('#total_weight').val()) || 0;
            let baseWeight = parseFloat($('#per_weight').val()) || 0;
            let basePayCost = parseFloat($('#per_pay_cost').val()) || 0;

            let convertedWeight, convertedPay;

            if (rate === 0 || isNaN(rate)) {
                alert("No exchange rate found.");
                $('#per_weight').val(baseWeight);
                $('#per_pay_cost').val(basePayCost);
                return;
            }
            console.log('sdfsdf',data.currency)
            if ({!! $settings->currency_id !!} != data.currency) {

                    $("#total_div").css('display', "block")
                    $("#balance_dev").css('display', "block")
                    $("#per_weight_div").css('display', "block")
                    $("#exchange_rate_div").css('display', "block")
                    $("#equalent_balance_dev").css('display', "block")
                    $("#new_total_dev").css('display', "none")
                    $("#balance_div").css('display', "none")
                    $("#paid_div_equalent").css('display', "block")
                    $("#paid_div").css('display', "none")

                } else {
                    $("#exchagne_type_div").css('display', "none")
                    $("#total_div").css('display', "none")
                    $("#per_weight_div").css('display', "none")
                    $("#balance_dev").css('display', "none")
                    $("#equalent_balance_dev").css('display', "none")
                    $("#new_total_dev").css('display', "block")
                    $("#balance_div").css('display', "block")
                    $("#paid_div_equalent").css('display', "none")
                    $("#paid_div").css('display', "block")
            }
            if (operation === 'multiply') {
                convertedWeight = baseWeight * rate;
                convertedPay = basePayCost * rate;
            } else if (operation === 'divide') {
                convertedWeight = baseWeight / rate;
                convertedPay = basePayCost / rate;
            } else {
                convertedWeight = baseWeight;
                convertedPay = basePayCost;
            }


            $('#per_weight_equalent').val(convertedPay.toFixed(2));
            $('#total_equalent').val(TotalWeight * $('#per_weight_equalent').val());
            $('#equalent_balance').val($('#total_equalent').val() - $('#paid_equalent').val());
            $('#new_balance').val($('#new_total').val() - $('#paid').val());


            // Optional: show rate details
            $('#rates').val(rate);
            $('#operations').val(operation);
            $('#exchange_type').val(operation);
        },
        error: function (xhr) {
            alert('Error fetching rate.');
        }
    });
});
    @isset($cargo)
          $.ajax({
        url: '/get_latest_exchange_rate/' + {!! $cargo->account_id !!},
        type: 'GET',
        success: function (response) {
            var data = response.rate;

            let rate = parseFloat(data.rate);
            let operation = data.operation;

            let TotalWeight = parseFloat($('#total_weight').val()) || 0;
            let baseWeight = parseFloat($('#per_weight').val()) || 0;
            let basePayCost = parseFloat($('#per_pay_cost').val()) || 0;

            let convertedWeight, convertedPay;

            if (rate === 0 || isNaN(rate)) {
                alert("No exchange rate found.");
                $('#per_weight').val(baseWeight);
                $('#per_pay_cost').val(basePayCost);
                return;
            }
            console.log('sdfsdf',data.currency)
            if ({!! $settings->currency_id !!} != data.currency) {

                    $("#total_div").css('display', "block")
                    $("#balance_dev").css('display', "block")
                    $("#per_weight_div").css('display', "block")
                    $("#exchange_rate_div").css('display', "block")
                    $("#equalent_balance_dev").css('display', "block")
                    $("#new_total_dev").css('display', "none")
                    $("#balance_div").css('display', "none")
                       $("#paid_div_equalent").css('display', "block")
                    $("#paid_div").css('display', "none")

                } else {
                    $("#exchagne_type_div").css('display', "none")
                    $("#total_div").css('display', "none")
                    $("#per_weight_div").css('display', "none")
                    $("#balance_dev").css('display', "none")
                    $("#equalent_balance_dev").css('display', "none")
                    $("#new_total_dev").css('display', "block")
                    $("#balance_div").css('display', "block")
                       $("#paid_div_equalent").css('display', "none")
                    $("#paid_div").css('display', "block")
            }
            if (operation === 'multiply') {
                convertedWeight = baseWeight * rate;
                convertedPay = basePayCost * rate;
            } else if (operation === 'divide') {
                convertedWeight = baseWeight / rate;
                convertedPay = basePayCost / rate;
            } else {
                convertedWeight = baseWeight;
                convertedPay = basePayCost;
            }


            $('#per_weight_equalent').val(convertedPay.toFixed(2));
            $('#total_equalent').val(TotalWeight * $('#per_weight_equalent').val());
            $('#equalent_balance').val($('#total_equalent').val() - $('#paid_equalent').val());
            $('#new_balance').val($('#new_total').val() - $('#paid').val());



            // Optional: show rate details
            $('#rates').val(rate);
            $('#exchange_type').val(operation);
        },
        error: function (xhr) {
            alert('Error fetching rate.');
        }
    });
    @endisset

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
    $('#ajaxSubmit').click(function(){

    // console.log('btn clicked');
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
       url: "/client",
       method: 'post',
       data: {
          name: $('#name').val(),
          nid: $('#nid').val(),
          zipcode: $('#zipcode').val(),
          country_id: $('#country_id').val(),
          mobile: $('#mobile').val(),
          permanent: $('#permanent').val(),

          address: $('#address').val(),
       },
       success: function(result){
           if(result.errors)
           {
               $('.alert-danger').html('');
               $.each(result.errors, function(key, value){
                   $('.alert-danger').show();
                   $('.alert-danger').append('<li>'+value+'</li>');
               });
           }
           else
           {
               $('.alert-danger').hide();
               $('#open').hide();
               $('#clientForm').modal('hide');
            // RELOAD DROPDOWN WITH NEW CLIENT NAME
               $.ajax({

                    url: "{{ route('cargo.index_reload') }}",
                    //  url: "/client/reload",
                    method: 'GET',
                    success: function(data) {
                        if ($('#client_type').val() == 'sender') {
                        $('#client').html(data.html);

                        } else if ($('#client_type').val() == 'receiver') {
                            $('#receiver').html(data.html);
                        }

                        // $("#father").val(data.father);
                    }
                });
                $('#name').val('');
                $('#country_id').val('');
                $('#mobile').val('');
                $('#zipcode').val('');
                $('#permanent').val('');
                // $('#is_sanctioned').val('');
                // $('#is_pep').val('');
                // $('#risk_level').val('');
                $('#address').val('');

           }
       }
    });
});
</script>

@endsection

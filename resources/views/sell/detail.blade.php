@extends('layouts.app')
@section('title', 'sells Detail')

@section('content')

<div class="card mt-4">
    @if (session()->has('success') || session()->has('error') )
    @include('layouts.partials.components.alert')
    @endif
    <div class="card-header">
        <h3 class="card-title">{{ __('home.sell_details') }}</h3>
        <div class=" mx-5 alert alert-success" id="success-message error-message"></div>

    </div>

    <div class="card-body pt-4">
        <div class="grid-margin">
            <div class="">
                <div class="panel panel-primary">
                    <div class="tab-menu-heading ">
                        <div class="tabs-menu">
                            <!-- Tabs -->
                            <ul class="nav panel-tabs panel-success">
                                <li><a href="#tab1" class="active" data-bs-toggle="tab">{{ __('home.sell_items') }}</a></li>
                                {{-- <li><a href="#tab2" data-bs-toggle="tab" class="text-dark">{{ __('home.new_item') }}</a></li> --}}
                            </ul>
                        </div>
                    </div>
                    <div class="panel-body tabs-menu-body border-0 pt-4">
                        <div class="tab-content">
                            <div class="tab-pane active" id="tab1">
                                <div class="table-responsive">
                                    <table id="file-datatable" class="table table-bordered table-striped text-nowrap mb-0 table-hover">
                                        <thead class="border-top">
                                            <tr>
                                                <th class="d-none">{{ __('home.no') }}</th>
                                                <th>{{ __('home.no') }}</th>
                                                <th>{{ __('home.product') }}</th>
                                                <th>{{ __('home.stock') }}</th>
                                                <th>{{ __('home.unit') }}</th>
                                                <th>{{ __('home.quantity') }}</th>
                                                <th>{{ __('home.sell_price') }}</th>
                                                <th>{{__('home.profit')}}</th>
                                                <th>{{ __('home.cbm') }}</th>
                                                <th>{{ __('home.total') }}</th>
                                                <th>{{ __('home.action') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php $c =1; @endphp
                                            @foreach($details as $detail)
                                            <tr>
                                                <td class="d-none">{{$detail->id}}</td>
                                                <td>{{$c++}}</td>
                                                <td>{{$detail->product->name}}</td>
                                                <td>{{$detail->stock_product->stock->name}}</td>
                                                <td>{{$detail->product->unit->name}}</td>

                                                <td>{{ number_format($detail->quantity) }}</td>
                                                <td>{{ ($detail->cost) }}</td>
                                                <td>{{ ($detail->profit) }}</td>
                                                <td>{{ number_format($detail->cbm ) }}</td>
                                                <td>{{ number_format($detail->cost * $detail->quantity) }}</td>
                                                <td>
                                                    <button class="btn-save btn btn-sm btn-outline-success" style="display:none;">{{ __('home.update') }}</button>
                                                    <button class="btn-edit btn btn-sm btn-outline-primary">{{ __('home.edit') }}</button>

                                                    <!-- Add the confirmation modal using Bootstrap -->
                                                    <button type="button" class="btn text-danger btn-sm" data-bs-toggle="modal" data-bs-target="#confirmationModal{{ $detail->id }}">
                                                        <span class="fe fe-trash-2 fs-16"></span>
                                                    </button>

                                                    <!-- Confirmation Modal -->
                                                    <div class="modal fade " id="confirmationModal{{$detail->id }}">
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h6 class="modal-title">Confirmation</h6><button aria-label="Close" class="btn-close" data-bs-dismiss="modal"><span aria-hidden="true">&times;</span></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    Are you sure you want to delete this record?
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                                    <form action="{{ route('sell.detail.delete', $detail) }}" method="POST" class="d-inline">
                                                                        @method('delete')
                                                                        @csrf
                                                                        <button type="submit" class="btn btn-danger">Delete</button>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            @php $c++; @endphp

                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>


                            <div class="tab-pane " id="tab2">
                                <form action="{{route('sell.detail.insert', $sells) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    @method('POST')

                                    <div class="table-responsive">

                                        <div class="form-row mb-3">

                                            <div class="col-sm-12 mt-3">
                                                <h4>{{__('home.products')}}</h4>
                                                <hr>
                                            </div>
                                            <div class="col-xl-4 mb-3">
                                                <label for="validationServer01">{{ __('home.rate') }}</label>
                                                <input type="text" class="form-control form-control @error('rate') {{'is-invalid'}} @enderror" name="rate" autocomplete="off" id="rate" value="1">
                                                @error('date')
                                                <div id="" class="invalid-feedback">{{$message}}</div>
                                                @enderror
                                            </div>
                                            <input type="hidden" name="currency_id" id="currency_id" value="{{$sells->currency_id}}">

                                            <div class="col-sm-12">
                                                <div class="table-responsive">
                                                    <span id="result"></span>
                                                    <table class="table table-bordered table-striped" id="user_table">
                                                        <thead>
                                                            <tr>
                                                                <th width="15%">{{__('home.stock')}}</th>
                                                                <th width="15%">{{__('home.product')}}</th>
                                                                <th width="15%">{{__('home.type')}}</th>
                                                                <th width="1%">{{__('home.currency')}}</th>
                                                                <th width="10%">{{__('home.income_price')}}</th>
                                                                <th width="10%">{{__('home.quantity')}}</th>
                                                                <th width="10%">{{__('home.cost')}}</th>
                                                                <th width="11%">{{__('home.cbm')}}</th>

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

                                        <div class=" row mb-4" dir="{{App::getLocale() == 'en' ? 'ltr' : 'rtl'}}">
                                            <label class="col-md-2 {{App::getLocale() == 'en' ? 'offset-sm-7' : ''}} form-label text-right fw-bold">{{ __('home.grand_total') }}</label>
                                            <div class="col-md-3">
                                                <input type="number" step="0.01" id="total" name="total" class="form-control" readonly="" value="0">
                                            </div>
                                        </div>
                                        <div class=" row mb-4" dir="{{App::getLocale() == 'en' ? 'ltr' : 'rtl'}}">
                                            <label class="col-md-2 {{App::getLocale() == 'en' ? 'offset-sm-7' : ''}} form-label text-right fw-bold">{{ __('home.total_cbm') }}</label>
                                            <div class="col-md-3">
                                                <input type="number" step="0.01" id="total_cbm" name="total_cbm" class="form-control" readonly="" value="0">
                                            </div>
                                        </div>

                                    </div>

                                    <div class="form-footer mt-2">
                                        <input type="submit" class="btn btn-primary" value="{{ __('home.save') }}">
                                    </div>

                                </form>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- card-body --}}
</div>
@endsection



@section('pagescript')

<script>
    $(document).ready(function() {
        var table = $('#file-datatable').DataTable();

        // Handle Edit button click
        $('#file-datatable tbody').on('click', '.btn-edit', function() {
            // Remove contenteditable attribute from all cells
            $('td[contenteditable="true"]').removeAttr('contenteditable');

            // Add contenteditable attribute to the cells in the clicked row
            var row = $(this).closest('tr');
            row.find('td:eq(5), td:eq(6)').attr('contenteditable', 'true');

            // Show the Save button for the clicked row
            row.find('.btn-save').show();

            // Hide the Edit button for the clicked row
            row.find('.btn-edit').hide();
        });

        // Handle Save button click
        $('#file-datatable tbody').on('click', '.btn-save', function() {
            var row = $(this).closest('tr');
            var quantity = row.find('td:eq(5)').text().trim();
            var cost = row.find('td:eq(6)').text().trim();
            var id = row.find('td:eq(0)').text().trim();

            // Ensure that both quantity and cost have values before sending the request
            if (quantity !== '' && cost !== '') {
                sendDataToServer(row, quantity, cost, id);
            } else {
                alert('Please enter both quantity and cost before saving.');
            }

            // Remove contenteditable attribute from all cells
            $('td[contenteditable="true"]').removeAttr('contenteditable');

            // Hide the Save button for all rows
            $('.btn-save').hide();

            // Show the Edit button for all rows
            $('.btn-edit').show();
        });

        function sendDataToServer(row, quantity, cost, id) {
            // AJAX request to submit data to the Laravel controller
            var url = "{{ url('/sell-detail/update') }}";
            var _token = "{{ csrf_token() }}";

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                _token: _token,
                url: url,
                type: 'POST',
                data: {
                    quantity: quantity,
                    cost: cost,
                    id: id,
                    // Add more fields as needed
                },
                success: function(response) {
                    // console.log(response);
                    $('#success-message').html(response[1]).fadeIn().delay(3000).fadeOut();
                },
                error: function(error) {
                    // console.error(error);
                    $('#error-message').html('An error occurred. Please try again.').fadeIn().delay(3000).fadeOut();
                }
            });
        }


        // ADD NEW ITEM TAB



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
            html += "<td id='types" + count + "'>" +
                '<select class="form-select form-control change_type" onchange="CalulateData(this, ' + count + ')" name="change_type[]" id="change_type">' +
                "<option value=''>Select Action</option>" + // Add this line for default selection
                "<option value='multiply'>Multiply</option>" + // Option for multiplication
                "<option value='divide'>Divide</option>" + // Option for division
                "</select> " +
                "</td>";
            html += '<td><input type="hidden" name="to_currency_id[]" readonly id="to_currency_id' + count + '"  class="form-control" value="" /><input type="text" name="to_currency_name[]" readonly id="to_currency_name' + count + '"  class="form-control" value="" /></td>';
            html += '<td><input type="hidden" step="0.01" name="original_purchase[]"id="original_purchase' + count + '"  oninput="calculate()" class="form-control" value="0.00" /><input type="number" name="purchase[]" readonly id="purchases' + count + '" oninput="calculateSell(this)" class="form-control" value="0" /></td>';
            html += '<td><input type="number" name="quantity[]" oninput="calculate()" class="form-control" value="0" /></td>';
            html += '<td><input type="hidden" step="0.01" name="original_sell[]"id="original_sell' + count + '"  oninput="calculate()" class="form-control" value="0.00" /><input type="number" step="0.01" name="cost[]"id="costs' + count + '"  oninput="calculate()" class="form-control" value="0.00" /></td>';
            html += '<td><input type="hidden" step="0.01" name="height[]"id="height' + count + '"  oninput="calculate()" class="form-control" value="0.00" /><input type="hidden" step="0.01" name="width[]"id="width' + count + '"  oninput="calculate()" class="form-control" value="0.00" /><input type="hidden" step="0.01" name="length[]"id="length' + count + '"  oninput="calculate()" class="form-control" value="0.00" /><input type="number" id="cbm' + count + '" name="cbm[]" readonly oninput="calculate()" class="form-control" value="0" /></td>';
            html += '<td><input type="number" name="total[]" readonly oninput="calculate()" class="form-control" value="0" /></td>';
            if (number > 1) {
                html += '<td><button type="button" name="remove" id="" class="btn btn-danger remove"> <i class="fa fa-minus text-white"></i></button></td></tr>';
                $('#tbody').append(html);
            } else {
                html += '<td><button type="button" name="add" id="add" class="btn btn-success btn btn-primary"> <i class="fa fa-plus text-white"></i></button></td></tr>';
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
                var total_cbm = height * width * length;
                grandTotal += total;
                grandTotalCBM += total_cbm;

                // Update the total input field in the current row
                $(this).find('[name="total[]"]').val(total.toFixed(2));
                $(this).find('[name="cbm[]"]').val(total_cbm.toFixed(2));
            });

            // Update the grand_total input field
            $('#total').val(grandTotal.toFixed(2));
            $('#total_cbm').val(grandTotalCBM.toFixed(2));

        });



    });

    function loadProducts(select, count) {
        var stockProductId = $(select).val();
        // alert(count);

        $.ajax({
            url: '/get-products/' + stockProductId,
            type: 'GET',
            success: function(response) {
                var products = response.products;
                var currency = response.currency;

                var options = "<option value=''>Select Product</option>";
                products.forEach(function(product) {
                    options += "<option value='" + product.id + "' data-cost='" + (product.product.cost + product.product.expense)  + "' data-height='" + product.product.height + "' data-width='" + product.product.width  + "' data-length='" + product.product.length    + "'  data-original_purchase='" + (product.product.cost + product.product.expense) + "' data-original_sell='" + (product.product.sell ? product.product.sell : 0) + "' data-currency='" + (product.product.currency_id) + "' data-sell='" + (product.product.sell ? product.product.sell : 0) + "'>" + product.product.name + " (" + product.quantity + ")</option>";

                });
                $('.products' + count).html(options);


            },
            error: function(xhr, status, error) {
                console.error(error);

            }
        });
    }

    function CalulateData(select, count) {
        var selectedOption = $(select).find('option:selected');

        // Get the data-sell value
        var rate = parseFloat($('#rate').val()); // Convert to number
        var costValue_old = parseFloat($('#purchases' + count).val()); // Convert to number
        var sellValue_old = parseFloat($('#costs' + count).val()); // Convert to number
        var currency_id = $('#currency_id').val();

        var to_currency_id = $('#to_currency_id' + count).val();
        var originalSellValue = $('#original_sell' + count).val();
        var originalPurchaseValue = $('#original_purchase' + count).val();



        // Use the original sell value
        var sellValue = originalSellValue
        var PurchaseValue = originalPurchaseValue


        if (currency_id != to_currency_id) {
            var sellValueNew, costValueNew; // Initialize variables
            var type = $(selectedOption).val();

            if (type === "multiply") {

                sellValueNew = (sellValue * rate).toFixed(2);
                costValueNew = (PurchaseValue * rate).toFixed(2);
            } else if (type === "divide") {

                sellValueNew = (sellValue / rate).toFixed(2);
                costValueNew = (PurchaseValue / rate).toFixed(2);
            }
        } else {


            sellValueNew = sellValue;
            costValueNew = PurchaseValue;
        }

        // Update the value of costs

        $('#to_currency_id' + count).val(to_currency_id);
        $('#costs' + count).val(sellValueNew);
        $('#purchases' + count).val(costValueNew);

    }

    function calculate() {
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
                var total_cbm = height * width * length;
                grandTotal += total;
                grandTotalCBM += total_cbm;

                // Update the total input field in the current row
                $(this).find('[name="total[]"]').val(total.toFixed(2));
                $(this).find('[name="cbm[]"]').val(total_cbm.toFixed(2));
        });

        // Update the grand_total input field
        $('#total').val(grandTotal.toFixed(2));
            $('#total_cbm').val(grandTotalCBM.toFixed(2));
            $('#balance').val(grandTotal.toFixed(2));
    }

    $('.select').select2();


    function CurrencyData(select, count) {

        $.ajax({
            url: '/get-product-currency/' + select,
            type: 'GET',
            success: function(response) {
                var data = response.data;
                $('#to_currency_name' + count).val(data.product.currency.name);
                $('#cbm'+count).val(data.product.height * data.product.width *data.product.length);
            },
            error: function(xhr, status, error) {
                console.error(error);

            }
        });

    }

    function calculateSell(select) {
        // Get the selected option
        var selectedOption = $(select).find('option:selected');
        var type = $(selectedOption).val();


        // Get the data-sell value
        var rate = $('#rate').val();
        var sellValue = selectedOption.data('sell') || 0;
        var costValue = selectedOption.data('cost') || 0;
        var original_sell = selectedOption.data('original_sell') || 0;
        var original_purchase = selectedOption.data('original_purchase') || 0;
        var to_currency_id = selectedOption.data('currency');
        var currency_id = $('#currency_id').val();
        var width = selectedOption.data('width') || 0;
        var height = selectedOption.data('height') || 0;
        var length = selectedOption.data('length') || 0;
        // if (currency_id != to_currency_id) {

        //     if (currency_id ==1) {
        //         sellValue = (sellValue * rate).toFixed(2); // Perform multiplication and round off to 2 decimal places
        //         costValue = (costValue * rate).toFixed(2); // Perform multiplication and round off to 2 decimal places
        //     } else if (currency_id == 2) {
        //         sellValue = (sellValue / rate).toFixed(2); // Perform division and round off to 2 decimal places
        //         costValue = (costValue / rate).toFixed(2); // Perform division and round off to 2 decimal places
        //     }

        // } else {

        //     sellValue = (sellValue).toFixed(2);
        //     costValue = (costValue).toFixed(2);
        // }
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


    $(document).on('change', 'select.select2', function() {
        // Call calculateSell when product[] dropdown changes
        calculateSell(this);
    });

    $(document).ready(function() {
        // Call calculateSell for each select2 dropdown on document ready
        $('select.select2').each(function() {
            calculateSell(this);
        });

    });
</script>
@endsection

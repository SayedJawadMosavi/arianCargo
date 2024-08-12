@extends('layouts.app')

@section('title', 'Receive Purchase')
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
            {{ __('home.received') }}
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
        <form action="{{isset($received) ? route('received.update', $received) : route('received.store')}}" method="POST" enctype="multipart/form-data">
            @csrf
            @if(isset($received))
            @method('PUT')
            @else
            @method('POST')
            @endif

            <div class="form-row mb-3">

                <div class="col-xl-4 mb-3">
                    <label for="validationServer04">{{ __('home.account') }}</label>
                    <select class="form-select form-control @error('account') {{'is-invalid'}} @enderror" id="account_id" aria-describedby="validationServer04Feedback" name="account_id">
                        @foreach($accounts as $account)
                        <option value="{{$account->id}}" @if(isset($purchases)) @if($purchases->account_id == $account->id) selected="selected" @endif @endif> {{ $account->name }} - {{ $account->currency->name }} - {{ $account->amount }}</option>
                        @endforeach
                    </select>
                    @error('account_id')
                    <div id="" class="invalid-feedback">{{$message}}</div>
                    @enderror
                </div>

                @if ($settings->date_type=='shamsi')
                <div class="col-xl-4 mb-3">
                    <label for="validationServer01">{{ __('home.date') }}</label>
                    <input type="text" class="form-control form-control @error('date') {{'is-invalid'}} @enderror" name="shamsi_date" autocomplete="off" id="dates" value="{{isset($received) ? $received->shamsi_date : old('date')}}">
                    <input type="hidden" class="form-control form-control" name="purchase_id" autocomplete="off" id="purchase_id" value="{{$purchases->id}}">

                    @error('date')
                    <div id="" class="invalid-feedback">{{$message}}</div>
                    @enderror
                </div>
                @else
                <div class="col-xl-4 mb-3">
                    <label for="validationServer01">{{ __('home.date') }}</label>
                    <input type="date" class="form-control @error('date') {{'is-invalid'}} @enderror" id="date" name="miladi_date" value="{{ isset($received) ? $received->miladi_date : date('Y-m-d') }}">
                    @error('date')
                    <div id="" class="invalid-feedback">{{$message}}</div>
                    @enderror
                </div>
                @endif



                <div class="col-xl-12 mb-3">
                    <label for="validationServer01">{{ __('home.description') }}</label>
                    <input type="text" class="form-control @error('description') {{'is-invalid'}} @enderror" id="description" name="description" value="{{isset($received) ? $received->description : old('description')}}">
                    @error('description')
                    <div id="" class="invalid-feedback">{{$message}}</div>
                    @enderror
                </div>

            </div>

            @if(!isset($received))
            @if(count($products))

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
                                    <th width="22%">{{__('home.product')}}</th>
                                    <th width="10%">{{__('home.remaining')}}</th>
                                    <th width="10%">{{__('home.qty')}}</th>
                                    <th width="15%">{{ __('home.sell') }}</th>
                                    <th width="10%">{{__('home.rent')}}</th>
                                    <th width="8%" class="">{{__('home.expense')}}</th>

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
            @endif
            <div class=" row mb-4" dir="{{App::getLocale() == 'en' ? 'ltr' : 'rtl'}}">
                <label class="col-md-2 {{App::getLocale() == 'en' ? 'offset-sm-7' : ''}}  form-label text-right fw-bold">{{ __('home.grand_total') }}</label>
                <div class="col-md-3">
                    <input type="number" step="0.01" id="total" name="total" class="form-control" readonly="" value="{{isset($received) ? $received->total : old('total', 0)}}">
                </div>
            </div>
            <!-- <div class=" row mb-4" dir="{{App::getLocale() == 'en' ? 'ltr' : 'rtl'}}">
                <label class="col-md-2 {{App::getLocale() == 'en' ? 'offset-sm-7' : ''}}  form-label text-right fw-bold">{{ __('home.paid') }}</label>
                <div class="col-md-3">
                    <input type="number" step="0.01" id="paid" name="paid" class="form-control" value="{{isset($received) ? $received->paid : old('paid', 0)}}">
                </div>
            </div> -->
            <div class=" row mb-4" dir="{{App::getLocale() == 'en' ? 'ltr' : 'rtl'}}">
                <label class="col-md-2 {{App::getLocale() == 'en' ? 'offset-sm-7' : ''}}  form-label text-right fw-bold">{{ __('home.balance') }}</label>
                <div class="col-md-3">
                    <input type="number" step="0.01" id="balance" name="balance" class="form-control" readonly="" value="{{isset($received) ? $received->balance : old('balance', 0)}}">
                </div>
            </div>



            <div class="form-footer mt-2">
                <input type="submit" class="btn btn-primary" value="@if(isset($received))
                    {{ __('home.update') }}
                @else
                    {{ __('home.save') }}
                @endif">
            </div>

        </form>

    </div>
    <div class="col-sm-12">
        <div class="table-responsive">
            <table id="file-datatable" class="table table-bordered table-striped text-nowrap mb-0 table-hover">
                <thead class="border-top">
                    <tr>
                        <th class="d-none">{{ __('home.no') }}</th>
                        <th>{{ __('home.no') }}</th>
                        <th>{{ __('home.product') }}</th>
                        <th>{{ __('home.quantity') }}</th>

                        <th>{{ __('home.sell') }}</th>
                        <th>{{ __('home.expense') }}</th>
                        <th>{{ __('home.rent') }}</th>

                        <th>{{ __('home.action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @php $c =1; @endphp
                    @foreach($receieves as $detail)
                    <tr>
                        <td class="d-none">{{$detail->id}}</td>
                        <td>{{$c++}}</td>
                        <td>{{$detail->product->name}}</td>
                        <td>{{$detail->quantity}}</td>
                        <td>{{$detail->sell_price}}</td>

                        <td>{{$detail->expense}}</td>
                        <td>{{$detail->rent}}</td>

                        <td>
                            <button class="btn-save btn btn-sm btn-outline-success" style="display:none;">{{ __('home.update') }}</button>
                            <button class="btn-edit btn btn-sm btn-outline-primary">{{ __('home.edit') }}</button>

                            <!-- Add the confirmation modal using Bootstrap -->
                            <!-- <button type="button" class="btn text-danger btn-sm" data-bs-toggle="modal" data-bs-target="#confirmationModal{{ $detail->id }}">
                                <span class="fe fe-trash-2 fs-16"></span>
                            </button> -->

                            <!-- Confirmation Modal -->

                        </td>
                    </tr>
                    <div class="modal fade " id="confirmationModal{{ $detail->id }}">
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
                                    <form action="{{ route('purchase.receive.delete', $detail) }}" method="POST" class="d-inline">
                                        @method('delete')
                                        @csrf
                                        <button type="submit" class="btn btn-danger">Delete</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    @php $c++; @endphp

                    @endforeach
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
        $(document).ready(function() {
            var table = $('#file-datatable').DataTable();

            // Handle Edit button click
            $('#file-datatable tbody').on('click', '.btn-edit', function() {
                // Remove contenteditable attribute from all cells
                $('td[contenteditable="true"]').removeAttr('contenteditable');

                // Add contenteditable attribute to the cells in the clicked row
                var row = $(this).closest('tr');
                row.find('td:eq(3), td:eq(4),td:eq(5)').attr('contenteditable', 'true');

                // Show the Save button for the clicked row
                row.find('.btn-save').show();

                // Hide the Edit button for the clicked row
                row.find('.btn-edit').hide();
            });

            // Handle Save button click
            $('#file-datatable tbody').on('click', '.btn-save', function() {
                var row = $(this).closest('tr');
                var quantity = row.find('td:eq(3)').text().trim();
                var expense = row.find('td:eq(4)').text().trim();
                var rent = row.find('td:eq(5)').text().trim();
                var id = row.find('td:eq(0)').text().trim();

                // Ensure that both quantity and expense have values before sending the request
                if (quantity !== '' && expense !== '' && rent !== '') {
                    sendDataToServer(row, quantity, expense, rent, id);
                } else {
                    alert('Please enter both quantity and expense before saving.');
                }

                // Remove contenteditable attribute from all cells
                $('td[contenteditable="true"]').removeAttr('contenteditable');

                // Hide the Save button for all rows
                $('.btn-save').hide();

                // Show the Edit button for all rows
                $('.btn-edit').show();
            });

            function sendDataToServer(row, quantity, expense, rent, id) {
                // AJAX request to submit data to the Laravel controller
                var url = "{{ url('/purchase-receive/update') }}";
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
                        expense: expense,
                        rent: rent,
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

            var count = 1;

            dynamic_field(count);

            function dynamic_field(number) {
                html = '<tr>';
                html += "<td>" +
                    '<select class="form-select form-control select2" onchange="GetRemaining(this.value, ' + count + ')"  name="product[]" id="product">' +
                    +"@foreach ($products as $obj)" +
                    "<option value='{{$obj->id}}' >" + '{{$obj->product->name}}' + ' (Qty: {{$obj->quantity}})' + "</option>" +
                    "@endforeach" +
                    "</select> " +
                    "</td>"
                html += '<td><input type="hidden" name="purchase_detail_id[]" id="purchase_detail_id' + count + '" oninput="calculate()" class="form-control" value="0" /><input type="number" readonly name="remaining[]" id="remainings' + count + '" oninput="calculate()" class="form-control" value="0" /></td>';
                // html += "<td id='types"+count+"'>" +
                //     '<select class="form-select form-control change_type" onchange="CalulateData(this, ' + count + ')" name="change_type[]" id="change_type">' +
                //     "<option value=''>Select Action</option>" + // Add this line for default selection
                //     "<option value='multiply'>Multiply</option>" + // Option for multiplication
                //     "<option value='divide'>Divide</option>" + // Option for division
                //     "</select> " +
                //     "</td>";
                // html += '<td><input type="hidden" name="to_currency_id[]" readonly id="to_currency_id' + count + '"  class="form-control" value="" /><input type="text" name="to_currency_name[]" readonly id="to_currency_name' + count + '"  class="form-control" value="" /></td>';
                html += '<td><input type="number" name="quantity[]" oninput="calculate()" class="form-control" value="0" /><input type="hidden" name="received[]" id="received' + count + '" oninput="calculate()" class="form-control" value="0" /></td>';
                html += '<td><input type="number" name="sell[]" step="0.01" class="form-control" value="0" /></td>';
                html += '<td><input type="number" step="0.01" name="rent[]"  oninput="calculate()" class="form-control" value="0.00" /></td>';
                html += '<td class=""><input type="number" step="0.01" name="expense[]" oninput="calculate()" class="form-control" value="0" /></td>';

                html += '<td><input type="number" readonly name="total[]" step="0.01" oninput="calculate()" class="form-control" value="0" /></td>';
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
                var selectedProduct = $("#product").find('option:selected').val(); // add newly just to insert the purchase_daetail id
                GetRemaining(selectedProduct, count); // add newly just to insert the purchase_daetail id
                dynamic_field(count);
            });

            $(document).on('click', '.remove', function() {
                count--;
                $(this).closest("tr").remove();


                var grandTotal = 0;
                var balance = 0;

                $('#tbody tr').each(function() {
                    var quantity = parseFloat($(this).find('[name="quantity[]"]').val()) || 0;
                    var rent = parseFloat($(this).find('[name="rent[]"]').val()) || 0;
                    var expense = parseFloat($(this).find('[name="expense[]"]').val()) || 0;

                    var total = quantity * (rent + expense);
                    grandTotal += total;

                    // Update the total input field in the current row
                    $(this).find('[name="total[]"]').val(total.toFixed(2));
                });

                // Update the grand_total input field
                $('#total').val(grandTotal.toFixed(2));

            });




            // Call calculateSell for each select2 dropdown on document ready



        });

        $(document).ready(function() {
            // Call GetRemaining for the initially selected product on document ready
            var selectedProduct = $("#product").find('option:selected').val();
            var count = 1; // Assuming a default value for count, adjust as needed
            GetRemaining(selectedProduct, count);

            // Bind change event to the product dropdown to call GetRemaining when selection changes
            $("#product").change(function() {
                var selectedProduct = $(this).val();
                GetRemaining(selectedProduct, count);
            });
        });

        function GetRemaining(select, count) {

            $.ajax({
                url: '/get-product-remaining/' + select,
                type: 'GET',
                success: function(response) {
                    var data = response.data;
                    $('#remainings' + count).val(data.quantity - data.received);
                    $('#received' + count).val(data.received);
                    $('#purchase_detail_id' + count).val(data.id);
                    $('#to_currency_id' + count).val(data.product.currency_id);
                },
                error: function(xhr, status, error) {
                    console.error(error);

                }
            });

        }

        function calculate() {
            var grandTotal = 0;
            var balance = 0;

            $('#tbody tr').each(function() {
                var quantity = parseFloat($(this).find('[name="quantity[]"]').val()) || 0;
                var rent = parseFloat($(this).find('[name="rent[]"]').val()) || 0;
                var expense = parseFloat($(this).find('[name="expense[]"]').val()) || 0;

                var total = quantity * (rent + expense);
                grandTotal += total;

                // Update the total input field in the current row
                $(this).find('[name="total[]"]').val(total.toFixed(2));
            });

            // Update the grand_total input field
            $('#total').val(grandTotal.toFixed(2));
            $('#balance').val(grandTotal.toFixed(2));
        }

        $('#paid').keyup(function() {

            var total = parseFloat($('#total').val());
            var paid = parseFloat($('#paid').val());

            $('#balance').val(total - paid);
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
                                <input type="text" class="form-control @error('company') {{'is-invalid'}} @enderror" id="company" name="company" \>
                                <input type="hidden" class="form-control" name="type" value="purchase">
                            </div>
                            <div class="col-xl-4 col-sm-4 mb-3">
                                <label for="validationServer01">{{ __('home.contact_person') }}</label>
                                <input type="text" class="form-control @error('contact_person') {{'is-invalid'}} @enderror" id="contact_person" name="contact_person">
                                @error('contact_person')
                                <div id="" class="invalid-feedback">{{$message}}</div>
                                @enderror
                            </div>

                            <div class="col-xl-4 col-sm-4 mb-3">
                                <label for="validationServer01">{{ __('home.mobile') }}</label>
                                <input type="text" class="form-control @error('mobile') {{'is-invalid'}} @enderror" id="mobile" name="mobile">
                                @error('mobile')
                                <div id="" class="invalid-feedback">{{$message}}</div>
                                @enderror
                            </div>
                            <div class="col-xl-4 col-sm-4 mb-3">
                                <label for="validationServer01">{{ __('home.license') }}</label>
                                <input type="text" class="form-control @error('license') {{'is-invalid'}} @enderror" id="license" name="license">
                                @error('license')
                                <div id="" class="invalid-feedback">{{$message}}</div>
                                @enderror
                            </div>
                            <div class="col-xl-8 col-sm-12 mb-3">
                                <label for="validationServer01">{{ __('home.address') }}</label>
                                <input type="text" class="form-control @error('address') {{'is-invalid'}} @enderror" id="address" name="address">
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

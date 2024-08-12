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
                                <li><a href="#tab1" class="active" data-bs-toggle="tab">{{  __('home.sell_items') }}</a></li>
                                <li><a href="#tab2" data-bs-toggle="tab" class="text-dark">{{ __('home.new_item') }}</a></li>
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
                                                <th>{{ __('home.quantity') }}</th>
                                                <th>{{ __('home.sell_price') }}</th>
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
                                                <td>{{$detail->quantity}}</td>
                                                <td>{{$detail->cost}}</td>
                                                <td>
                                                    <button class="btn-save btn btn-sm btn-outline-success" style="display:none;">{{ __('home.update') }}</button>
                                                    <button class="btn-edit btn btn-sm btn-outline-primary">{{ __('home.edit') }}</button>

                    <!-- Add the confirmation modal using Bootstrap -->
                    <button type="button" class="btn text-danger btn-sm" data-bs-toggle="modal" data-bs-target="#confirmationModal{{ $c }}">
                        <span class="fe fe-trash-2 fs-16"></span>
                    </button>

                    <!-- Confirmation Modal -->
                    <div class="modal fade " id="confirmationModal{{ $c }}">
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
                                        <div class="col-sm-12">
                                            <div class="table-responsive">
                                                <span id="result"></span>
                                                <table class="table table-bordered table-striped" id="user_table">
                                                    <thead>
                                                        <tr>
                                                            <th width="35%">{{__('home.product')}}</th>
                                                            <th width="15%">{{__('home.quantity')}}</th>
                                                            <th width="15%">{{__('home.cost')}}</th>
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

                                    <div class=" row mb-4">
                                        <label class="col-md-2 offset-sm-7 form-label text-right fw-bold">Grand Total</label>
                                        <div class="col-md-3">
                                            <input type="number" step="0.01" id="total" name="total" class="form-control" readonly="" value="0">
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
    var table = $('#data-table').DataTable();

    // Handle Edit button click
    $('#data-table tbody').on('click', '.btn-edit', function () {
        // Remove contenteditable attribute from all cells
        $('td[contenteditable="true"]').removeAttr('contenteditable');

        // Add contenteditable attribute to the cells in the clicked row
        var row = $(this).closest('tr');
        row.find('td:eq(3), td:eq(4)').attr('contenteditable', 'true');

        // Show the Save button for the clicked row
        row.find('.btn-save').show();

        // Hide the Edit button for the clicked row
        row.find('.btn-edit').hide();
    });

    // Handle Save button click
    $('#data-table tbody').on('click', '.btn-save', function () {
        var row = $(this).closest('tr');
        var quantity = row.find('td:eq(3)').text().trim();
        var cost = row.find('td:eq(4)').text().trim();
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
                '<select class="form-select form-control select" name="product[]" id="to_currency">' +
                +"@foreach ($products as $obj)" +
                "<option value='{{$obj->id}}' data-cost='{{$obj->cost}}'>" + '{{$obj->name}}' + ' (' + '{{$obj->quantity}}' + ')' +"</option>" +
                "@endforeach" +
                "</select> " +
                "</td>"
            html += '<td><input type="number" name="quantity[]" oninput="calculate()" class="form-control" value="0" /></td>';
            html += '<td><input type="number" step="0.01" name="cost[]"  oninput="calculate()" class="form-control" value="0.00" /></td>';
            html += '<td><input type="number" name="total[]" readonly oninput="calculate()" class="form-control" value="0" /></td>';
            if (number > 1) {
                html += '<td><button type="button" name="remove" id="" class="btn btn-danger remove"> <i class="fa fa-minus"></i></button></td></tr>';
                $('#tbody').append(html);
            } else {
                html += '<td><button type="button" name="add" id="add" class="btn btn-success btn btn-primary"> <i class="fa fa-plus"></i></button></td></tr>';
                $('#tbody').html(html);
            }
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
    }

    $('.select').select2();


</script>
@endsection

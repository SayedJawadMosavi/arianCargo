@extends('layouts.app')

@section('title', 'New Main Transfer')
@section('content')
    <div class="card mt-4">
        @if (session()->has('success') || session()->has('error'))
            @include('layouts.partials.components.alert')
        @endif

        <div class="card-header d-flex justify-content-between">
            <h4 class="card-title">
                @if(isset($main_transfer))
                    {{ __('home.edit_main_transfer') }}
                @else
                    {{ __('home.new_main_transfer') }}
                @endif
            </h4>
            <a href="{{route('main_transfer.index')}}" class="btn btn-primary">{{ __('home.all_main_transfers') }}</a>
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

        <div class="card-body">
            <form action="{{isset($main_transfer) ? route('main_transfer.update', $main_transfer) : route('main_transfer.store')}}" method="POST" enctype="multipart/form-data">
                @csrf
                @if(isset($main_transfer))
                    @method('PUT')
                @endif

                <div class="form-row mb-3">
                    @if ($settings->date_type=='shamsi')

                    <div class="col-xl-3 mb-3">
                        <label for="validationServer01">{{ __('home.date') }}</label>
                        <input type="text" class="form-control form-control @error('date') {{'is-invalid'}} @enderror" name="shamsi_date" autocomplete="off" id="dates" value="{{isset($main_transfer) ? $main_transfer->shamsi_date : old('date')}}">
                        @error('date')
                        <div id="" class="invalid-feedback">{{$message}}</div>
                        @enderror
                    </div>
                    @else
                    <div class="col-xl-3 mb-3">
                        <label for="validationServer01">{{ __('home.date') }}</label>
                        <input type="date" class="form-control @error('date') {{'is-invalid'}} @enderror" id="date" name="miladi_date" value="{{ isset($main_transfer) ? $main_transfer->miladi_date : date('Y-m-d') }}">
                        @error('date')
                        <div id="" class="invalid-feedback">{{$message}}</div>
                        @enderror
                    </div>
                    @endif
                    <div class="col-xl-2 mb-3">
                        <label for="bill">{{ __('home.bill') }}</label>
                        <input type="text" class="form-control @error('bill') is-invalid @enderror" name="bill" value="{{ isset($main_transfer) ? $main_transfer->bill : old('bill') }}">
                        @error('bill')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-xl-4 mb-3">
                        <label for="validationServer04">{{ __('home.stock') }}</label>
                        <select class="form-select form-control  @error('stock_id') {{'is-invalid'}} @enderror" id="validationServer04" aria-describedby="validationServer04Feedback" required name="stock_id">
                            <option selected disabled value="">Choose...</option>
                            @foreach($stocks as $stock)
                                <option value="{{$stock->id}}" @if(isset($main_transfer)) @if($main_transfer->stock_id == $stock->id) selected = 'selected' @endif @endif >{{$stock->name}}</option>
                            @endforeach
                        </select>
                        @error('stock_id')
                        <div id="" class="invalid-feedback">{{$message}}</div>
                        @enderror
                    </div>
                    <div class="col-xl-12 mb-3">
                        <label for="description">{{ __('home.description') }}</label>
                        <input type="text" class="form-control @error('description') is-invalid @enderror" name="description" value="{{ isset($main_transfer) ? $main_transfer->description : old('description') }}">
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                @if(!isset($main_transfer))
                    <div class="form-row mb-3">
                        <div class="col-sm-12 mt-3">
                            <h4>{{ __('home.products') }}</h4>
                            <hr>
                        </div>
                        <div class="col-sm-12">
                            <div class="table-responsive">
                                <span id="result"></span>
                                <table class="table table-bordered table-striped" id="user_table">
                                    <thead>
                                        <tr>
                                            <th width="25%">{{ __('home.product') }}</th>
                                            {{-- <th width="25%">{{ __('home.stock') }}</th> --}}
                                            <th width="15%">{{ __('home.available') }}</th>
                                            <th width="15%">{{ __('home.quantity') }}</th>
                                            <th width="10%">{{ __('home.action') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tbody">
                                        <!-- Dynamic rows will be appended here -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="form-footer mt-2">
                    <input type="submit" class="btn btn-primary" value="{{ isset($main_transfer) ? __('home.update') : __('home.save') }}">
                </div>
            </form>
        </div>
    </div>
@endsection

@section('pagescript')
<script>
    $(document).ready(function() {
        var count = 1;

        dynamic_field(count);

        function dynamic_field(number) {
            var html = '<tr>';
            html += '<td><select class="form-select form-control select2" name="product[]" onchange="loadAvailable(this, ' + number + ')"><option value="">{{ __('home.please_select') }}</option>@foreach ($products as $obj)<option value="{{ $obj->product_id }}" data-qty="{{ $obj->total_quantity }}">{{ $obj->product->name }} - {{ $obj->product->model }} ({{ $obj->total_quantity }})</option>@endforeach</select></td>';
            // html += '<td><select class="form-select form-control " name="stock[]"><option value="">{{ __('home.please_select') }}</option>@foreach ($stocks as $stock)<option value="{{ $stock->id }}">{{ $stock->name }}</option>@endforeach</select></td>';
            html += '<td><input type="number" name="available[]" readonly class="form-control" value="0" /></td>';
            html += '<td><input type="number" name="quantity[]" class="form-control" value="0" oninput="validateQuantity(this)" /></td>';
            if (number > 1) {
                html += '<td><button type="button" name="remove" class="btn btn-danger remove"><i class="fa fa-minus"></i></button></td></tr>';
                $('#tbody').append(html);
            } else {
                html += '<td><button type="button" name="add" id="add" class="btn btn-success btn-primary"><i class="fa fa-plus"></i></button></td></tr>';
                $('#tbody').html(html);
            }
            reinitializeSelect2();
        }

        $(document).on('click', '#add', function() {
            count++;
            dynamic_field(count);
        });

        $(document).on('click', '.remove', function() {
            count--;
            $(this).closest('tr').remove();
        });

        window.loadAvailable = function(selectElement, rowNumber) {
            var selectedOption = selectElement.options[selectElement.selectedIndex];
            var quantity = selectedOption.getAttribute('data-qty');
            $(selectElement).closest('tr').find('input[name="available[]"]').val(quantity);
        };

        function reinitializeSelect2() {
            $('.select2').select2();
        }

        window.validateQuantity = function(inputElement) {
            var row = $(inputElement).closest('tr');
            var available = parseFloat(row.find('input[name="available[]"]').val()) || 0;
            var quantity = parseFloat(inputElement.value) || 0;

            if (quantity > available) {
                alert("Quantity cannot be greater than available amount.");
                inputElement.value = available;
            }
        }

        reinitializeSelect2(); // Initial call to initialize Select2
    });
</script>
@endsection

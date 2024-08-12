@extends('layouts.app')

@section('title', 'New stock_transfer')
@section('content')

<div class="card mt-4">
    <div class="card-header d-flex justify-content-between">
        <h4 class="card-title">
            @if(isset($stockTransfer))
            {{ __('home.stock_transfer') }}
            @else
            {{ __('home.stock_transfer') }}
            @endif
        </h4>
        <a href="{{route('stock_transfer.index')}}" class="btn btn-primary">{{ __('home.stock_transfers') }}</a>
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
        <form action="{{isset($stockTransfer) ? route('stock_transfer.update', $stockTransfer) : route('stock_transfer.store')}}" method="POST" enctype="multipart/form-data">
            @csrf
            @if(isset($stockTransfer))
            @method('PUT')
            @else
            @method('POST')
            @endif

            <div class="form-row mb-3">

                <div class="col-xl-4 mb-3">
                    <label for="validationServer04">{{ __('home.from_stock') }}</label>
                    <select class="form-select form-control select2 @error('stock_id') {{'is-invalid'}} @enderror" id="validationServer04" aria-describedby="validationServer04Feedback" required name="from_stock" id="from_stock" onchange="showData(this.value);">
                        <option selected disabled value="">Choose...</option>
                        @foreach($stocks as $stock)
                        <option value="{{$stock->id}}" @if(isset($stockTransfer)) @if($stockTransfer->sender_stock_id == $stock->id) selected = 'selected' @endif @endif >{{$stock->name}}</option>
                        @endforeach
                    </select>
                    @error('stock_id')
                    <div id="" class="invalid-feedback">{{$message}}</div>
                    @enderror
                </div>
                <div class="col-xl-4 mb-3">
                    <label for="validationServer04">{{ __('home.to_stock') }}</label>
                    <select class="form-select form-control  @error('stock') {{'is-invalid'}} @enderror" aria-describedby="validationServer04Feedback" required name="to_stock" id="to_stock">
                        <option selected disabled value="">Choose...</option>

                    </select>
                    @error('stock_id')
                    <div id="" class="invalid-feedback">{{$message}}</div>
                    @enderror
                </div>
                <div class="col-xl-4 mb-3">
                    <label for="validationServer04">{{ __('home.product') }}</label>
                    <select class="form-select form-control  @error('product') {{'is-invalid'}} @enderror" aria-describedby="validationServer04Feedback" required name="product_id" id="product_id">
                        <option selected disabled value="">Choose...</option>

                    </select>
                    @error('product_id')
                    <div id="" class="invalid-feedback">{{$message}}</div>
                    @enderror
                </div>

                @if ($settings->date_type=='shamsi')
                <div class="col-xl-4 mb-3">
                    <label for="validationServer01">{{ __('home.date') }}</label>
                    <input type="text" class="form-control form-control @error('date') {{'is-invalid'}} @enderror" name="shamsi_date" autocomplete="off" id="dates" value="{{isset($sell) ? $sell->shamsi_date : old('date')}}">
                    @error('date')
                    <div id="" class="invalid-feedback">{{$message}}</div>
                    @enderror
                </div>
                @else
                <div class="col-xl-4 mb-3">
                    <label for="validationServer01">{{ __('home.date') }}</label>
                    <input type="date" class="form-control @error('date') {{'is-invalid'}} @enderror" id="date" name="miladi_date" value="{{ isset($sell) ? $sell->miladi_date : date('Y-m-d') }}">
                    @error('date')
                    <div id="" class="invalid-feedback">{{$message}}</div>
                    @enderror
                </div>
                @endif

                <div class="col-xl-4 mb-3">
                    <label for="validationServer01">{{ __('home.quantity') }}</label>
                    <input type="number" class="form-control @error('quantity') {{'is-invalid'}} @enderror" id="quantity" name="quantity" value="{{isset($stockTransfer) ? $stockTransfer->quantity : old('quantity', 0) }}">
                    @error('quantity')
                    <div id="" class="invalid-feedback">{{$message}}</div>
                    @enderror
                </div>

                <div class="col-12 col-sm-12">
                    <label class="" for="inlineFormInputGroup">{{__('home.description')}}</label>
                    <textarea name="description" class="form-control" id="description">{{isset($stockTransfer) ? $stockTransfer->description: old('description')}}</textarea>
                    @error('description')
                    <span class="alert text-danger">{{$message}}</span>
                    @enderror
                </div>
            </div>

            <div class="form-footer mt-2">
                <input type="submit" class="btn btn-primary" value="@if(isset($stockTransfer))
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
    function showData(value) {
        $.ajax({
            url: "{{URL::asset('/')}}" + "getStock/" + value,
            method: 'GET',
            success: function(data) {
                $("#to_stock").empty();
                $("#to_stock").append("<option value=''>حساب را انتخاب نمایید</option>");
                $('#to_stock').html(data.html);
                // Reapply classes
                $('#to_stock').removeClass('select1 is-invalid');
                $('#to_stock').addClass('form-select form-control');
                // Reinitialize select2
                $('#to_stock').select2();
            }
        });

        $.ajax({
            url: "{{URL::asset('/')}}" + "getStockProducts/" + value,
            method: 'GET',
            success: function(data1) {
                $("#product_id").empty();
                $("#product_id").append("<option value=''>حساب را انتخاب نمایید</option>");
                $('#product_id').html(data1.html);
                $('#product_id').removeClass('select1 is-invalid');
                $('#product_id').addClass('form-select form-control');

                // Reinitialize select2
                $('#product_id').select2();
            }
        });

    }


    @isset($stockTransfer)

    $.ajax({
        url: "{{URL::asset('/')}}" + "getStock/" + {
            !!$stockTransfer - > sender_stock_id!!
        },
        method: 'GET',
        success: function(data) {
            $("#to_stock").empty();
            $("#to_stock").append("<option value=''>حساب را انتخاب نمایید</option>");
            $('#to_stock').html(data.html);
            $("#to_stock option").each(function() {
                // console.log('Text:-' + this.text + '  Value:-' + this.value);
                if ($(this).val() == {
                        !!$stockTransfer - > receiver_stock_id!!
                    }) {
                    $(this).attr("selected", "selected");
                }
            });
        }
    });

    $.ajax({
        type: 'POST',
        url: "{{URL::asset('/')}}" + "getStockProducts/" + {
            !!$stockTransfer - > sender_stock_id!!
        },
        method: 'GET',

        success: function(data1) {
            $("#product_id").empty();
            $("#product_id").append("<option value=''>حساب را انتخاب نمایید</option>");
            $('#product_id').html(data1.html);

            $("#product_id option").each(function() {
                console.log('Text:-' + this.text + '  Value:-' + this.value);
                if ($(this).val() == {
                        !!$stockTransfer - > sender_product_id!!
                    }) {
                    $(this).attr("selected", "selected");
                }
            });

        }
    });

    @endisset
</script>

@endsection

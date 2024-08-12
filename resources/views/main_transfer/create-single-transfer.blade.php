@extends('layouts.app')

@section('title', 'New main_transfer')
@section('content')

    <div class="card mt-4">
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
        <div class="card-body ">
            @if (session()->has('success') || session()->has('error') )
                @include('layouts.partials.components.alert')
            @endif

            <form action="{{isset($main_transfer) ? route('main_transfer.update', $main_transfer) : route('main_transfer.store')}}" method="POST" enctype="multipart/form-data">
                @csrf
                @if(isset($main_transfer))
                    @method('PUT')
                @else
                    @method('POST')
                @endif

                <div class="form-row mb-3">
                    {{-- @dd($products) --}}
                    <div class="col-xl-4 mb-3">
                        <label for="validationServer04">{{ __('home.product') }}</label>
                        <select class="form-select form-control select2 @error('product') {{'is-invalid'}} @enderror" id="validationServer04" aria-describedby="validationServer04Feedback" required name="product_id">
                            @foreach($products as $product)
                            <option value="{{$product->product_id}}" @if(isset($main_transfer)) @if($main_transfer->product_id == $product->id) selected = 'selected' @endif @endif> {{ $product->product->name }} - {{ $product->total_quantity }}</option>
                            @endforeach
                        </select>
                        @error('product_id')
                        <div id="" class="invalid-feedback">{{$message}}</div>
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
                            <input type="date" class="form-control @error('date') {{'is-invalid'}} @enderror" id="date"  name="miladi_date" value="{{ isset($sell) ? $sell->miladi_date : date('Y-m-d') }}">
                            @error('date')
                            <div id="" class="invalid-feedback">{{$message}}</div>
                            @enderror
                        </div>
                    @endif

                    <div class="col-xl-4 mb-3">
                        <label for="validationServer01">{{ __('home.quantity') }}</label>
                        <input type="number" class="form-control @error('quantity') {{'is-invalid'}} @enderror" id="quantity"  name="quantity" value="{{isset($main_transfer) ? $main_transfer->quantity : old('quantity', 0) }}">
                        @error('quantity')
                        <div id="" class="invalid-feedback">{{$message}}</div>
                        @enderror
                    </div>

                    <div class="col-xl-4 mb-3">
                        <label for="validationServer01">{{ __('home.min_stock') }}</label>
                        <input type="number" class="form-control @error('min_stock') {{'is-invalid'}} @enderror" id="min_stock"  name="min_stock" value="{{isset($main_transfer) ? $main_transfer->min_stock : old('min_stock', 0) }}">
                        @error('min_stock')
                        <div id="" class="invalid-feedback">{{$message}}</div>
                        @enderror
                    </div>
                    <div class="col-12 col-sm-12">
                        <label class="" for="inlineFormInputGroup">{{__('home.description')}}</label>
                        <textarea name="description" class="form-control" id="description">{{isset($main_transfer) ? $main_transfer->description: old('description')}}</textarea>
                        @error('description')
                        <span class="alert text-danger">{{$message}}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-footer mt-2">
                    <input type="submit" class="btn btn-primary" value="@if(isset($main_transfer))
                    {{ __('home.update') }}
                @else
                    {{ __('home.save') }}
                @endif">
                </div>

            </form>

        </div>
    </div>

@endsection


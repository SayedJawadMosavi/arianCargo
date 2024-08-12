@extends('layouts.app')

@section('title', 'New Product')
@section('content')

<div class="card mt-4">
    @if (session()->has('success') || session()->has('error') )
        @include('layouts.partials.components.alert')
    @endif
    <div class="card-header d-flex justify-content-between">
        <h4 class="card-title">
            @if(isset($product))
            {{ __('home.edit_product') }}
            @else
            {{ __('home.new_product') }}
            @endif
        </h4>
        <a href="{{route('product.index')}}" class="btn btn-primary">{{ __('home.all_products') }}</a>
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
        <form action="{{isset($product) ? route('product.update', $product) : route('product.store')}}" method="POST" enctype="multipart/form-data">
            @csrf
            @if(isset($product))
            @method('PUT')
            @else
            @method('POST')
            @endif

            <div class="form-row mb-3">
                @if(isset($product))

                <div class="col-xl-4 px-3 px-xl-1">
                    <label for="validationServer04">{{ __('home.status') }}</label>
                    <div class="form-group">

                        <label class="custom-switch form-switch mb-0">
                            <input type="checkbox" name="active" class="custom-switch-input" @if(isset($product)) @if($product->active == 1) {{'checked'}} @endif @else {{ 'checked' }}@endif>
                            <span class="custom-switch-indicator custom-switch-indicator-md"></span>
                            <span class="custom-switch-description">{{ __('home.active') }}</span>
                        </label>
                    </div>
                </div>


                @endif
                <div class="col-xl-4 mb-3">
                    <label for="validationServer04">{{ __('home.category') }}
                        <span class="badge bg-primary">
                            <a class="" data-bs-effect="effect-sign" data-bs-toggle="modal" href="#modaldemo8"><span class="badge bg-primary"> <i class="fe fe-plus "></i></span></a>
                    </label>
                    <select class="form-select form-control select2 @error('category_id') {{'is-invalid'}} @enderror" id="validationServer04" aria-describedby="validationServer04Feedback" required name="category_id">
                        <option selected disabled value="">Choose...</option>
                        @foreach($categories as $category)
                        <option value="{{$category->id}}" @if(isset($product)) @if($product->category_id == $category->id) selected = 'selected' @endif @endif >{{$category->name}}</option>
                        @endforeach
                    </select>
                    @error('category_id')
                    <div id="" class="invalid-feedback">{{$message}}</div>
                    @enderror
                </div>

                <div class="col-xl-4 mb-3">
                    <label for="validationServer04">{{ __('home.unit') }}</label>
                    <select class="form-select form-control @error('unit') {{'is-invalid'}} @enderror" id="validationServer04" aria-describedby="validationServer04Feedback" required name="unit_id">
                        @foreach($units as $unit)
                        <option value="{{$unit->id}}" @if(isset($product)) @if($product->unit_id == $unit->id) selected = 'selected' @endif @endif> {{ $unit->name }}</option>
                        @endforeach
                    </select>
                    @error('unit_id')
                    <div id="" class="invalid-feedback">{{$message}}</div>
                    @enderror
                </div>

                <div class="col-xl-4 mb-3">
                    <label for="validationServer01">{{ __('home.name') }}</label>
                    <input type="text" class="form-control @error('name') {{'is-invalid'}} @enderror" id="name" name="name" value="{{isset($product) ? $product->name : old('name')}}" autocomplete="off">
                    @error('name')
                    <div id="" class="invalid-feedback">{{$message}}</div>
                    @enderror
                </div>

                <div class="col-xl-4 mb-3">
                    <label for="validationServer01">{{ __('home.code') }}</label>
                    <input type="text" class="form-control @error('code') {{'is-invalid'}} @enderror" id="code" name="code" value="{{isset($product) ? $product->code : old('code')}}" autocomplete="off">
                    @error('code')
                    <div id="" class="invalid-feedback">{{$message}}</div>
                    @enderror
                </div>
                <div class="col-xl-4 mb-3">
                    <label for="validationServer01">{{ __('home.model') }}</label>
                    <input type="text" class="form-control @error('model') {{'is-invalid'}} @enderror" id="model" name="model" value="{{isset($product) ? $product->model : old('model', 0)}}" autocomplete="off">
                    @error('model')
                    <div id="" class="invalid-feedback">{{$message}}</div>
                    @enderror
                </div>
                <div class="col-xl-4 mb-3">
                    <label for="validationServer01">{{ __('home.height') }} ({{ __('home.cm') }})</label>
                    <input type="number" class="form-control @error('height') {{'is-invalid'}} @enderror" step="0.01" id="height" name="height" value="{{isset($product) ? $product->height : old('height', 0)}}">
                    @error('height')
                    <div id="" class="invalid-feedback">{{$message}}</div>
                    @enderror
                </div>
                <div class="col-xl-4 mb-3">
                    <label for="validationServer01">{{ __('home.width') }} ({{ __('home.cm') }})</label>
                    <input type="number" class="form-control @error('width') {{'is-invalid'}} @enderror" step="0.01" id="width" name="width" value="{{isset($product) ? $product->width : old('width', 0)}}">
                    @error('width')
                    <div id="" class="invalid-feedback">{{$message}}</div>
                    @enderror
                </div>
                <div class="col-xl-4 mb-3">
                    <label for="validationServer01">{{ __('home.length') }} ({{ __('home.cm') }})</label>
                    <input type="number" class="form-control @error('length') {{'is-invalid'}} @enderror" step="0.01" d="length" name="length" value="{{isset($product) ? $product->length : old('length', 0)}}">
                    @error('length')
                    <div id="" class="invalid-feedback">{{$message}}</div>
                    @enderror
                </div>
                <div class="col-xl-4 mb-3">
                    <label for="validationServer01">{{ __('home.weight') }}</label>
                    <input type="number" class="form-control @error('weight') {{'is-invalid'}} @enderror" step="0.01" id="weight" name="weight" value="{{isset($product) ? $product->weight : old('weight', 0)}}">
                    @error('weight')
                    <div id="" class="invalid-feedback">{{$message}}</div>
                    @enderror
                </div>
                <!-- @if(!isset($product))
                <div class="col-xl-4 mb-3">
                    <label for="validationServer01">{{ __('home.quantity') }}</label>
                    <input type="number" class="form-control @error('quantity') {{'is-invalid'}} @enderror" id="quantity" name="quantity" value="0">
                    @error('quantity')
                    <div id="" class="invalid-feedback">{{$message}}</div>
                    @enderror
                </div>
                <div class="col-xl-4 mb-3">
                    <label for="validationServer01">{{ __('home.cost') }}</label>
                    <input type="number" step="0.01" class="form-control @error('cost') {{'is-invalid'}} @enderror" id="cost" name="cost" value="{{isset($product) ? $product->cost : old('cost')}}">
                    @error('cost')
                    <div id="" class="invalid-feedback">{{$message}}</div>
                    @enderror
                </div>
                <div class="col-xl-4 mb-3">
                    <label for="validationServer01">{{ __('home.sell') }}</label>
                    <input type="number" step="0.01" class="form-control @error('sell') {{'is-invalid'}} @enderror" id="sell" name="sell" value="{{isset($product) ? $product->sell : old('sell')}}">
                    @error('sell')
                    <div id="" class="invalid-feedback">{{$message}}</div>
                    @enderror
                </div>
                @endif -->
                <div class="col-xl-4 mb-3">
                    <label for="validationServer01">{{ __('home.min_stock') }}</label>
                    <input type="number" class="form-control @error('min_stock') {{'is-invalid'}} @enderror" id="min_stock" name="min_stock" value="{{isset($product) ? $product->min_stock : old('min_stock', 0) }}">
                    @error('min_stock')
                    <div id="" class="invalid-feedback">{{$message}}</div>
                    @enderror
                </div>

            </div>

            <div class="form-footer mt-2">
                <input type="submit" class="btn btn-primary" value="@if(isset($product))
                    {{ __('home.update') }}
                @else
                    {{ __('home.save') }}
                @endif">
            </div>

        </form>

    </div>
</div>

<div class="modal fade" id="modaldemo8">
    <div class="modal-dialog modal-dialog-centered text-center modal-sm" role="document">
        <div class="modal-content modal-content-demo">
            <div class="modal-header">
                <h6 class="modal-title">{{ __('home.new_category') }}</h6><button aria-label="Close" class="btn-close" data-bs-dismiss="modal"><span aria-hidden="true">&times;</span></button>
            </div>
            <form action="{{route('category.store')}}" method="POST" enctype="multipart/form-data">
                <div class="modal-body">

                    @csrf

                    @method('POST')


                    <div class="form-row mb-3">

                        <div class="col-xl-12 mb-3">
                            <label for="validationServer01">{{ __('home.name') }}</label>
                            <input type="text" class="form-control @error('name') {{'is-invalid'}} @enderror" id="name" name="name" value="">
                            <input type="hidden" class="form-control" name="type" value="product">
                            @error('name')
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

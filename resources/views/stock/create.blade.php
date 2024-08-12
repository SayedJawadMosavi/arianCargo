@extends('layouts.app')

@section('title', 'New stock')
@section('content')

    <div class="card mt-4">
        <div class="card-header d-flex justify-content-between">
            <h4 class="card-title">
                @if(isset($stock))
                    {{ __('home.edit_stock') }}
                @else
                    {{ __('home.new_stock') }}
                @endif
            </h4>
            <a href="{{route('stock.index')}}" class="btn btn-primary">{{ __('home.all_stocks') }}</a>

        </div>
        <div class="card-body ">
            @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
            <form action="{{isset($stock) ? route('stock.update', $stock) : route('stock.store')}}" method="POST" enctype="multipart/form-data">
                @csrf
                @if(isset($stock))
                    @method('PUT')
                @else
                    @method('POST')
                @endif

                <div class="form-row mb-3">

                    <div class="col-xl-4 mb-3">
                        <label for="validationServer01">{{ __('home.name') }}</label>
                        <input type="text" class="form-control @error('name') {{'is-invalid'}} @enderror" id="name"  name="name" value="{{isset($stock) ? $stock->name : old('name')}}" autocomplete="off">
                        @error('name')
                        <div id="" class="invalid-feedback">{{$message}}</div>
                        @enderror
                    </div>

                    <div class="col-xl-4 mb-3">
                        <label for="validationServer01">{{ __('home.contact_person') }}</label>
                        <input type="text" class="form-control @error('contact_person') {{'is-invalid'}} @enderror" id="contact_person"  name="contact_person" value="{{isset($stock) ? $stock->contact_person : old('contact_person')}}">
                        @error('contact_person')
                        <div id="" class="invalid-feedback">{{$message}}</div>
                        @enderror
                    </div>
                    <div class="col-xl-4 mb-3">
                        <label for="validationServer01">{{ __('home.mobile') }}</label>
                        <input type="number" class="form-control @error('mobile') {{'is-invalid'}} @enderror" id="mobile"  name="mobile" value="{{isset($stock) ? $stock->mobile : old('mobile')}}">
                        @error('mobile')
                        <div id="" class="invalid-feedback">{{$message}}</div>
                        @enderror
                    </div>

                    <div class="col-12 col-sm-12">
                        <label class="" for="inlineFormInputGroup">{{__('home.address')}}</label>
                        <textarea name="address" class="form-control" id="address">{{isset($stock) ? $stock->address: old('address')}}</textarea>
                        @error('address')
                        <span class="alert text-danger">{{$message}}</span>
                        @enderror
                    </div>

                </div>

                <div class="form-footer mt-2">
                    <input type="submit" class="btn btn-primary" value="@if(isset($stock))
                    {{ __('home.update') }}
                @else
                    {{ __('home.save') }}
                @endif">
                </div>

            </form>

        </div>
    </div>

@endsection


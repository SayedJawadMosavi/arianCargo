@extends('layouts.app')

@section('title', 'New category')
@section('content')

    <div class="card mt-4">
        <div class="card-header d-flex justify-content-between">
            <h4 class="card-title">
                @if(isset($category))
                    {{ __('home.edit_category') }}
                @else
                    {{ __('home.new_category') }}
                @endif
            </h4>
            <a href="{{route('asset_category.index')}}" class="btn btn-primary">{{ __('home.all_categories') }}</a>

        </div>
        <div class="card-body ">
            <form action="{{isset($category) ? route('asset_category.update', $category->id) : route('asset_category.store')}}" method="POST" enctype="multipart/form-data">
                @csrf
                @if(isset($category))
                    @method('PUT')
                @else
                    @method('POST')
                @endif

                <div class="form-row mb-3">


                    <div class="col-xl-4 mb-3">
                        <label for="validationServer01">{{ __('home.name') }}</label>
                        <input type="text" class="form-control @error('name') {{'is-invalid'}} @enderror" id="name"  name="name" value="{{isset($category) ? $category->name : old('name')}}" autocomplete="off">
                        @error('name')
                        <div id="" class="invalid-feedback">{{$message}}</div>
                        @enderror
                    </div>

                </div>

                <div class="form-footer mt-2">
                    <input type="submit" class="btn btn-primary" value="@if(isset($category))
                    {{ __('home.update') }}
                @else
                    {{ __('home.save') }}
                @endif">
                </div>

            </form>

        </div>
    </div>

@endsection


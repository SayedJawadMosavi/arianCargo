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
            <a href="{{route('category.index')}}" class="btn btn-primary">{{ __('home.all_categories') }}</a>

        </div>
        <div class="card-body ">
            <form action="{{isset($category) ? route('category.update', $category) : route('category.store')}}" method="POST" enctype="multipart/form-data">
                @csrf
                @if(isset($category))
                    @method('PUT')
                @else
                    @method('POST')
                @endif

                <div class="form-row mb-3">
                    @if(isset($category))

                        <div class="col-xl-2 px-3 px-xl-1">
                            <label for="validationServer04">{{ __('home.status') }}</label>
                            <div class="form-group">

                                <label class="custom-switch form-switch mb-0">
                                    <input type="checkbox" name="active" class="custom-switch-input" @if(isset($category)) @if($category->active == 1) {{'checked'}} @endif @else {{ 'checked' }}@endif>
                                    <span class="custom-switch-indicator custom-switch-indicator-md"></span>
                                    <span class="custom-switch-description">{{ __('home.active') }}</span>
                                </label>
                            </div>
                        </div>


                    @endif

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


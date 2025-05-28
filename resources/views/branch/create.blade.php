@extends('layouts.app')

@section('title', 'New branch')
@section('content')

<div class="col-xl-12">
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h4 class="card-title">
                @if(isset($branch))
                {{ __('home.edit_branch') }}
                @else
                {{ __('home.new_branch') }}
                @endif
            </h4>
            <a href="{{route('branch.index')}}" class="btn btn-primary">{{ __('home.all_branch') }} </a>
            @include('layouts.partials.components.alert')
        </div>
        <div class="card-body">
            <form action="{{isset($branch) ? route('branch.update', $branch->id) : route('branch.store')}}" method="POST" enctype="multipart/form-data">
                @csrf
                @if(isset($branch))
                @method('PUT')
                @else
                @method('POST')
                @endif

                <div class="form-row">
                    <div class="form-group col-md-6 mb-0">
                        <div class="form-group">
                            <label for=""> {{ __('home.name') }}</label>
                            <input type="text" class="form-control @error('name') {{'is-invalid'}} @enderror" id="name" placeholder=" name" name="name" value="{{isset($branch) ? $branch->name : old('name')}}" autocomplete="off">
                            @error('name')
                            <div id="" class="invalid-feedback">{{$message}}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group col-md-6 mb-0">
                        <div class="form-group">
                            <label for=""> {{ __('home.contact_person') }}</label>
                            <input type="text" class="form-control @error('contact_person') {{'is-invalid'}} @enderror" id="contact_person" placeholder=" {{ __('home.contact_person') }}" name="contact_person" value="{{isset($branch) ? $branch->contact_person : old('contact_person')}}">
                            @error('contact_person')
                            <div id="" class="invalid-feedback">{{$message}}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group col-md-6 mb-0">
                        <div class="form-group">
                            <label for="validationServer01">{{ __('home.mobile') }}</label>
                            <input type="text" class="form-control @error('mobile') {{'is-invalid'}} @enderror" id="mobile" name="mobile1" value="{{isset($branch) ? $branch->mobile : old('mobile')}}">
                            @error('mobile')
                            <div id="" class="invalid-feedback">{{$message}}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group col-md-6 mb-0">
                        <div class="form-group">
                            <label for="validationServer01">{{ __('home.mobile') }}</label>
                            <input type="text" class="form-control @error('mobile2') {{'is-invalid'}} @enderror" id="mobile2" placeholder="mobile2" name="mobile2" value="{{isset($branch) ? $branch->mobile2 : old('mobile2')}}">
                            @error('mobile2')
                            <div id="" class="invalid-feedback">{{$message}}</div>
                            @enderror
                        </div>


                    </div>
                    <div class="form-group col-md-6 mb-0">
                        <div class="form-group">
                            <label for="validationServer01">{{ __('home.start_bill') }}</label>
                            <input type="text" class="form-control @error('start_bill') {{'is-invalid'}} @enderror" id="start_bill" placeholder="start bill" name="start_bill" value="{{isset($branch) ? $branch->start_bill : old('start_bill')}}">
                            @error('start_bill')
                            <div id="" class="invalid-feedback">{{$message}}</div>
                            @enderror
                        </div>


                    </div>
                    <div class="form-group col-md-6 mb-0">
                        <div class="form-group">
                            <label for="validationServer01">{{ __('home.end_bill') }}</label>
                            <input type="text" class="form-control @error('end_bill') {{'is-invalid'}} @enderror" id="end_bill" placeholder="End bill" name="end_bill" value="{{isset($branch) ? $branch->end_bill : old('end_bill')}}">
                            @error('end_bill')
                            <div id="" class="invalid-feedback">{{$message}}</div>
                            @enderror
                        </div>


                    </div>

                    <div class="col-6 col-sm-6">
                        <label class="" for="inlineFormInputGroup">{{__('home.is_main_branch')}}</label>
                        <select class="form-control " name="is_main_branch" id="is_main_branch">
                            <option value="0">{{__('home.no')}}</option>
                            <option value="1">{{__('home.yes')}}</option>
                        </select>
                        @error('is_main_branch')
                        <span class="alert text-danger">{{$message}}</span>
                        @enderror
                    </div>

                    <div class="form-group col-md-6 mb-0">
                        <div class="form-group">
                            <label for=""> {{ __('home.photo') }}</label>
                            <input id="" type="file" name="photo" accept=".jpg, .png, image/jpeg, image/png" class=" form-control @error('description') {{'is-invalid'}} @enderror" id="inpFile" onchange="$('.image-preview__image')[0].src = window.URL.createObjectURL(this.files[0]);">
                            @error('photo')
                            <div id="" class="invalid-feedback">{{$message}}</div>
                            @enderror
                        </div>
                        <img src="/{{isset($branch) ? $branch->logo : old('logo')}}" class="image-preview__image" width="200px" /> <!--for preview purpose -->
                    </div>
                    <div class="form-group col-md-12 mb-0">
                        <div class="form-footer mt-2">
                            <input type="submit" class="btn btn-primary" value="@if(isset($branch))
                    {{ __('home.update') }}
                @else
                    {{ __('home.save') }}
                @endif">
                        </div>
                    </div>


            </form>

        </div>
    </div>
</div>

@endsection

@section('pagescript')
<script>

</script>
@endsection

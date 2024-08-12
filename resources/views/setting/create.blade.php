@extends('layouts.app')

@section('title', 'Settings')
@section('content')

<div class="card mt-4">
    <div class="card-header">
        <h4 class="card-title">
            Update Setting
        </h4>

    </div>
    <div class="card-body ">

        @if (session()->has('success') || session()->has('error') )
        @include('layouts.partials.components.alert')
        @endif

        @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif
        {{-- <form class="" action="{{ route('setting.post', $setting->id) }}" method="POST" enctype="multipart/form-data"> --}}
            <form action="{{isset($setting) ? route('setting.post') : route('setting.store')}}" method="POST" enctype="multipart/form-data">

            @csrf
            @method('POST')

            <fieldset>
                {{-- @dd($setting) --}}
                <div class="form-row mb-3">
                <div class="col-12 col-sm-4 col-md-3" @isset($setting) @if ($setting->check==1) style="pointer-events: none; opacity: 0.5;" @endif  @endisset>
                        <label class="custom-radio-lg" for="inlineFormInputGroup"> </label>
                        <input class="" type="hidden" name="id" id="id" value="{{ isset($setting) ? $setting->id : '' }}">

                        <div class="form-check form-check-inline">
                            <input class="form-check-input " type="radio" name="date_type" id="shamsi" value="shamsi" {{isset($setting) ? $setting->date_type == "shamsi" ? 'checked': '' :'checked' }}>
                            <label class="form-check-label" for="shamsi">
                                {{ __('home.shamsi_date') }}
                            </label>
                        </div>
                        <div class="form-check  form-check-inline">
                            <input class="form-check-input " type="radio" name="date_type" id="miladi" value="miladi" {{isset($setting) ? $setting->date_type == "miladi" ? 'checked': '' :'' }}>
                            <label class="form-check-label" for="miladi">
                                {{ __('home.miladi_date') }}
                            </label>
                        </div>
                    </div>
                    <div class="form-group  mb-3 col-md-4 mb-0" @isset($setting) @if ($setting->check==1) style="pointer-events: none; opacity: 0.5;" @endif @endisset>
                        <!-- <label for="validationServer04">{{ __('home.currency') }}</label> -->
                        <select class="form-select form-control @error('currency') {{'is-invalid'}} @enderror" id="validationServer04" aria-describedby="validationServer04Feedback"  name="currency_id">
                            <option selected disabled value="">Choose Main Currency...</option>
                            @foreach($currencies as $currency)

                                <option value="{{$currency->id}}" @if(isset($setting)) @if($setting->currency_id == $currency->id) selected = 'selected' @endif @endif> {{ $currency->name }}</option>
                            @endforeach
                        </select>
                        @error('currency_id')
                        <div id="" class="invalid-feedback">{{$message}}</div>
                        @enderror
                    </div>
                    <div class="form-group  mb-3 col-md-4 mb-0"@isset($setting) @if ($setting->check==1) style="pointer-events: none; opacity: 0.5;" @endif @endisset>

                        <label class="custom-switch form-switch mb-0">
                            <input type="checkbox" name="check" class="custom-switch-input" @if(isset($setting)) @if($setting->check == 1) {{'checked'}} @endif @else {{ 'checked' }}@endif>
                            <span class="custom-switch-indicator custom-switch-indicator-md"></span>
                            <span class="custom-switch-description">{{ __('home.check') }}</span>
                        </label>
                    </div>
                    <div class="form-group  mb-3 col-md-4 mb-0">
                        <input type="text" class="form-control @error('name_en') {{'is-invalid'}} @enderror" id="name_en" placeholder="English name" name="name_en" value="{{isset($setting) ? $setting->name_en : old('name_en')}}">
                        @error('name_en')
                        <div id="" class="invalid-feedback">{{$message}}</div>
                        @enderror
                    </div>
                    <div class="form-group mb-3 col-md-4 mb-0">
                        <input type="text" class="form-control @error('address_en') {{'is-invalid'}} @enderror" id="address_en" placeholder="English Address" name="address_en" value="{{isset($setting) ? $setting->address_en : old('address_en')}}">
                        @error('address_en')
                        <div id="" class="invalid-feedback">{{$message}}</div>
                        @enderror
                    </div>
                    <div class="form-group mb-3 col-md-4 mb-0">
                        <input type="text" class="form-control @error('mobile') {{'is-invalid'}} @enderror" id="mobile1" placeholder="mobile1" name="mobile1" value="{{isset($setting) ? $setting->mobile1 : old('mobile1')}}">
                        @error('mobile1')
                        <div id="" class="invalid-feedback">{{$message}}</div>
                        @enderror
                    </div>
                    <div class="form-group mb-3 col-md-4 mb-0">
                        <input type="text" class="form-control @error('mobile2') {{'is-invalid'}} @enderror" id="mobile2" placeholder="mobile2" name="mobile2" value="{{isset($setting) ? $setting->mobile2 : old('mobile2')}}">
                        @error('mobile2')
                        <div id="" class="invalid-feedback">{{$message}}</div>
                        @enderror
                    </div>
                    <div class="form-group mb-3 col-md-4 mb-0">
                        <input type="email" class="form-control @error('email') {{'is-invalid'}} @enderror" id="email" placeholder="email" name="email" value="{{isset($setting) ? $setting->email : old('email')}}">
                        @error('email')
                        <div id="" class="invalid-feedback">{{$message}}</div>
                        @enderror
                    </div>

                </div>

                <div class="form-row mb-3 d-none">

                    <div class="form-group col-md-4 mb-0 d-none">
                        <input type="text" class="form-control @error('second_address') {{'is-invalid'}} @enderror" id="second_address" placeholder="second address" name="second_address" value="{{isset($setting) ? $setting->second_address : old('second_address')}}">
                        @error('second_address')
                        <div id="" class="invalid-feedback">{{$message}}</div>
                        @enderror
                    </div>

                </div>
                <div class="form-row mb-3">

                    <div class="form-group col-md-4 mb-0">
                        <input type="text" class="form-control @error('facebook') {{'is-invalid'}} @enderror" id="facebook" placeholder="facebook" name="facebook" value="{{isset($setting) ? $setting->facebook : old('facebook')}}">
                        @error('facebook')
                        <div id="" class="invalid-feedback">{{$message}}</div>
                        @enderror
                    </div>
                    <div class="form-group col-md-4 mb-0">
                        <input type="text" class="form-control @error('twitter') {{'is-invalid'}} @enderror" id="twitter" placeholder="twitter" name="twitter" value="{{isset($setting) ? $setting->twitter : old('twitter')}}">
                        @error('twitter')
                        <div id="" class="invalid-feedback">{{$message}}</div>
                        @enderror
                    </div>

                </div>

                <div class="form-row mb-3">

                    <div class="form-group col-md-4 mb-0">
                        <div class="form-group">
                            <label for="" class="mt-3">Logo</label>

                            <input id="demo" type="file" name="logo" accept=".jpg, .png, image/jpeg, image/png, .webp, image/webp" class=" form-control @error('logo') {{'is-invalid'}} @enderror">
                            @error('logo')
                            <div id="" class="invalid-feedback">{{$message}}</div>
                            @enderror
                        </div>
                    </div>
                        <div class="form-group col-md-4 mb-0">
                            <div class="form-group">
                                <label for="" class="mt-3">Bill Header</label>
                                <input id="demo" type="file" name="bill_header" accept=".jpg, .png, image/jpeg, image/png, .webp, image/webp" class=" form-control @error('bill_header') {{'is-invalid'}} @enderror">
                                @error('bill_header')
                                <div id="" class="invalid-feedback">{{$message}}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="form-footer mt-2">
                        <input type="submit" class="btn btn-primary" value="Publish">
                    </div>
                </div>
            </fieldset>


        </form>

    </div>
</div>
@endsection

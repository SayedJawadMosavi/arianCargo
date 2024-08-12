@extends('layouts.app')

@section('title', 'New account')
@section('content')

    <div class="card mt-4">
            {{-- @dd(session()->has('success')) --}}
        @if (session()->has('success') || session()->has('error') )
            @include('layouts.partials.components.alert')
        @endif

        <div class="card-header d-flex justify-content-between">
            <h4 class="card-title">
                @if(isset($account))
                    {{ __('home.edit_account') }}
                @else
                    {{ __('home.new_account') }}
                @endif
            </h4>
            <a href="{{route('account.index')}}" class="btn btn-primary">{{ __('home.all_accounts') }}</a>

        </div>
        <div class="card-body ">
            <form action="{{isset($account) ? route('account.update', $account) : route('account.store')}}" method="POST" enctype="multipart/form-data">
                @csrf
                @if(isset($account))
                    @method('PUT')
                @else
                    @method('POST')
                @endif

                <div class="form-row mb-3">
                    @if(isset($account))

                        <div class="col-xl-4 px-3 px-xl-1">
                            <label for="validationServer04">{{ __('home.status') }}</label>
                            <div class="form-group">

                                <label class="custom-switch form-switch mb-0">
                                    <input type="checkbox" name="active" class="custom-switch-input" @if(isset($account)) @if($account->active == 1) {{'checked'}} @endif @else {{ 'checked' }}@endif>
                                    <span class="custom-switch-indicator custom-switch-indicator-md"></span>
                                    <span class="custom-switch-description">{{ __('home.active') }}</span>
                                </label>
                            </div>
                        </div>


                    @endif
                    <div class="col-xl-4 mb-3">
                        <label for="validationServer01">{{ __('home.name') }}</label>
                        <input type="text" class="form-control @error('name') {{'is-invalid'}} @enderror" id="name"  name="name" value="{{isset($account) ? $account->name : old('name')}}" autocomplete="off">
                        @error('name')
                        <div id="" class="invalid-feedback">{{$message}}</div>
                        @enderror
                    </div>
                    @if(!isset($account))

                    <div class="col-xl-4 mb-3">
                        <label for="validationServer04">{{ __('home.currency') }}</label>
                        <select class="form-select form-control @error('currency') {{'is-invalid'}} @enderror" id="validationServer04" aria-describedby="validationServer04Feedback" required name="currency_id">
                            <option selected disabled value="">Choose...</option>
                            @foreach($currencies as $currency)
                                <option value="{{$currency->id}}" @if(isset($account)) @if($account->currency_id == $currency->id) selected = 'selected' @endif @endif> {{ $currency->name }}</option>
                            @endforeach
                        </select>
                        @error('currency_id')
                        <div id="" class="invalid-feedback">{{$message}}</div>
                        @enderror
                    </div>

                        <div class="col-xl-4 mb-3">
                            <label for="validationServer01">{{ __('home.amount') }}</label>
                            <input type="number" step="0.1" class="form-control @error('amount') {{'is-invalid'}} @enderror" id="amount"  name="amount" value="{{isset($account) ? $account->amount : old('amount', 0)}}">
                            @error('amount')
                            <div id="" class="invalid-feedback">{{$message}}</div>
                            @enderror
                        </div>
                    @endif
                    <div class="col-xl-3 px-3 px-xl-1">
                        <label for="validationServer04">{{ __('home.default') }}</label>
                        <div class="form-group">

                            <label class="custom-switch form-switch mb-0">
                                <input type="checkbox" name="default" class="custom-switch-input" @if(isset($account)) @if($account->default == 1) {{'checked'}} @endif @endif>
                                <span class="custom-switch-indicator custom-switch-indicator-md"></span>
                                <span class="custom-switch-description">{{ __('home.yes') }}</span>
                            </label>
                        </div>
                    </div>

                    <div class="col-xl-9 mb-3">
                        <label for="validationServer01">{{ __('home.description') }}</label>
                        <input type="text" class="form-control @error('description') {{'is-invalid'}} @enderror" id="description"  name="description" value="{{isset($account) ? $account->description : old('description')}}">
                        @error('description')
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

@endsection


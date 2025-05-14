@extends('layouts.app')

@section('title', 'New Client')
@section('content')

<div class="card mt-4">
    @if (session()->has('success') || session()->has('error') )
    @include('layouts.partials.components.alert')
    @endif
    <div class="card-header d-flex justify-content-between">
        <h4 class="card-title">
            @if(isset($client))
            {{ __('home.edit_client') }}
            @else
            {{ __('home.new_client') }}
            @endif
        </h4>
        <a href="{{route('client.index')}}" class="btn btn-primary">{{ __('home.all_clients') }}</a>

    </div>
    <div class="card-body ">
        <form action="{{isset($client) ? route('client.update', $client) : route('client.store')}}" method="POST" enctype="multipart/form-data">
            @csrf
            @if(isset($client))
            @method('PUT')
            @else
            @method('POST')
            @endif

            <div class="form-row mb-3">
                @if(isset($client))

                <div class="col-xl-2 col-sm-2 px-3 px-xl-1">
                    <label for="validationServer04">{{ __('home.status') }}</label>
                    <div class="form-group">
                        <label class="custom-switch form-switch mb-0">
                            <input type="checkbox" name="active" class="custom-switch-input" @if(isset($client)) @if($client->active == 1) {{'checked'}} @endif @else {{ 'checked' }}@endif>
                            <span class="custom-switch-indicator custom-switch-indicator-md"></span>
                            <span class="custom-switch-description">{{ __('home.active') }}</span>
                        </label>
                    </div>
                </div>
                @endif


                <div class="col-xl-4 col-sm-4 mb-3">
                    <label for="validationServer01">{{ __('home.name') }}</label>
                    <input type="text" class="form-control @error('name') {{'is-invalid'}} @enderror" id="name" name="name" value="{{isset($client) ? $client->name : old('name')}}" autocomplete="off">
                    @error('name')
                    <div id="" class="invalid-feedback">{{$message}}</div>
                    @enderror
                </div>

                <div class="col-xl-4 col-sm-4 mb-3">
                    <label for="validationServer01">{{ __('home.mobile') }}</label>
                    <input type="number" class="form-control @error('mobile') {{'is-invalid'}} @enderror" id="mobile" name="mobile" value="{{isset($client) ? $client->mobile : old('mobile')}}" autocomplete="off">
                    @error('mobile')
                    <div id="" class="invalid-feedback">{{$message}}</div>
                    @enderror
                </div>

                <div class="col-md-3 mb-3">
                    <label for="validationServer01"> {{ __('home.zipcode') }} </label>
                    <input type="text" class="form-control @error('zipcode') {{'is-invalid'}} @enderror" id="zipcode" name="zipcode" value="{{isset($cargo) ? $cargo->zipcode : old('zipcode')}}">
                    @error('zipcode')
                    <div id="" class="invalid-feedback">{{$message}}</div>
                    @enderror
                </div>

                <div class="col-xl-3 mb-3">
                        <label for="validationServer04">{{ __('home.country') }}</label>
                        <select class="form-select form-control @error('country') {{'is-invalid'}} @enderror" id="validationServer04" aria-describedby="validationServer04Feedback" required name="country_id">
                            <option selected disabled value="">Choose...</option>
                            @foreach($countries as $country)
                                <option value="{{$country->id}}" @if(isset($client)) @if($client->country_id == $country->id) selected = 'selected' @endif @endif> {{ $country->name }}</option>
                            @endforeach
                        </select>
                        @error('country_id')
                        <div id="" class="invalid-feedback">{{$message}}</div>
                        @enderror
                    </div>
                <div class="col-xl-4 col-sm-4 mb-3 d-none">
                    <label for="validationServer01">{{ __('home.previous_balance') }}</label>
                    <input type="number" class="form-control @error('amount') {{'is-invalid'}} @enderror" id="amount" name="amount" value="{{isset($client) ? $client->amount : old('amount', 0)}}" autocomplete="off">
                    @error('amount')
                    <div id="" class="invalid-feedback">{{$message}}</div>
                    @enderror
                </div>


                <div class="col-xl-4 col-sm-4 mb-3">
                    <label for="validationServer01">{{ __('home.tazkira_no') }}</label>
                    <input type="text" class="form-control @error('nid') {{'is-invalid'}} @enderror" id="nid" name="nid" value="{{isset($client) ? $client->nid : old('nid')}}" autocomplete="off">
                    @error('nid')
                    <div id="" class="invalid-feedback">{{$message}}</div>
                    @enderror
                </div>
                <div class="col-xl-4 col-sm-4 mb-3">
                    <label for="validationServer01">{{ __('home.address') }}</label>
                    <input type="text" class="form-control @error('address') {{'is-invalid'}} @enderror" id="address" name="address" value="{{isset($client) ? $client->address : old('address')}}" autocomplete="off">
                    @error('address')
                    <div id="" class="invalid-feedback">{{$message}}</div>
                    @enderror
                </div>
            </div>

            <!-- <div class="form-row mb-3">

                @if(!isset($client) )
                <div class="col-sm-12 mt-3">
                    <h4>{{__('home.accounts')}}</h4>
                    <hr>
                </div>
                <div class="col-sm-12">
                    <div class="table-responsive">
                        <span id="result"></span>
                        <table class="table table-bordered table-striped" id="user_table">
                            <thead>
                                <tr>
                                    <th width="35%">{{__('home.currency')}}</th>
                                    <th width="35%">{{__('home.amount')}}</th>
                                    <th width="30%">{{__('home.action')}}</th>
                                </tr>
                            </thead>
                            <tbody id="tbody">

                            </tbody>

                        </table>
                    </div>
                </div>
                @endif

            </div> -->

            <div class="form-footer mt-2">
                <input type="submit" class="btn btn-primary" value="@if(isset($client))
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



@endsection

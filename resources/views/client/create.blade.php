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
        <form action="{{ isset($client) ? route('client.update', $client) : route('client.store') }}" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
            @csrf
            @if(isset($client))
            @method('PUT')
            @else
            @method('POST')
            @endif

            <div class="row g-3">

                @if(isset($client))
                <div class="col-md-2 d-flex align-items-center">
                    <label class="form-label me-2 fw-semibold">{{ __('home.status') }}</label>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="activeSwitch" name="active" @if($client->active == 1) checked @endif>
                        <label class="form-check-label" for="activeSwitch">{{ __('home.active') }}</label>
                    </div>
                </div>
                @endif

                <div class="col-md-4">
                    <label for="name" class="form-label fw-bold">
                        <i class="fa fa-user text-primary me-1"></i> {{ __('home.name') }}
                    </label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ $client->name ?? old('name') }}" autocomplete="off" required>
                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-4">
                    <label for="mobile" class="form-label fw-bold">
                        <i class="fa fa-phone text-success me-1"></i> {{ __('home.mobile') }}
                    </label>
                    <input type="number" class="form-control @error('mobile') is-invalid @enderror" id="mobile" name="mobile" value="{{ $client->mobile ?? old('mobile') }}" autocomplete="off" required>
                    @error('mobile') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-3">
                    <label for="zipcode" class="form-label fw-bold">
                        <i class="fa fa-bulk text-warning me-1"></i> {{ __('home.zipcode') }}
                    </label>
                    <input type="text" class="form-control @error('zipcode') is-invalid @enderror" id="zipcode" name="zipcode" value="{{ isset($cargo) ? $cargo->zipcode : old('zipcode') }}">
                    @error('zipcode') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-3">
                    <label for="country" class="form-label fw-bold">
                        <i class="fa fa-flag text-danger me-1"></i> {{ __('home.country') }}
                    </label>
                    <select class="form-select @error('country_id') is-invalid @enderror select2" id="country" name="country_id" required>
                        <option value="" selected disabled> {{ __('home.select') }}... </option>
                        @foreach($countries as $country)
                        <option value="{{ $country->id }}" @if(isset($client) && $client->country_id == $country->id) selected @endif>
                            {{ $country->name }}
                        </option>
                        @endforeach
                    </select>
                    @error('country_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-4 d-none">
                    <label for="amount" class="form-label fw-bold">{{ __('home.previous_balance') }}</label>
                    <input type="number" class="form-control @error('amount') is-invalid @enderror" id="amount" name="amount" value="{{ $client->amount ?? old('amount', 0) }}" autocomplete="off">
                    @error('amount') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-4">
                    <label for="nid" class="form-label fw-bold">
                        <i class="fa fa-id-card text-info me-1"></i> {{ __('home.tazkira_no') }}
                    </label>
                    <input type="text" class="form-control @error('nid') is-invalid @enderror" id="nid" name="nid" value="{{ $client->nid ?? old('nid') }}" autocomplete="off">
                    @error('nid') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-6 col-sm-2">
                    <label class="" for="inlineFormInputGroup">{{__('home.permanent')}}</label>
                    <select class="form-control " name="permanent" id="permanent">
                        <option value="no">{{__('home.no')}}</option>
                        <option value="yes">{{__('home.yes')}}</option>
                    </select>
                    @error('permanent')
                    <span class="alert text-danger">{{$message}}</span>
                    @enderror
                </div>
                <div class="col-md-4">
                    <label for="address" class="form-label fw-bold">
                        <i class="fa fa-map text-secondary me-1"></i> {{ __('home.address') }}
                    </label>
                    <input type="text" class="form-control @error('address') is-invalid @enderror" id="address" name="address" value="{{ $client->address ?? old('address') }}" autocomplete="off">
                    @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

            </div>

            <div class="mt-4 text-end">
                <button type="submit" class="btn btn-primary px-4">
                    <i class="fa fa-save me-1"></i>
                    @if(isset($client)) {{ __('home.update') }} @else {{ __('home.save') }} @endif
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('pagescript')
<script>
    $(document).ready(function() {
        $('.select2').select2();



    });
</script>


@endsection

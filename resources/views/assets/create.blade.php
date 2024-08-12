@extends('layouts.app')

@section('title', 'New asset')
@section('content')

<div class="col-xl-12">
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h4 class="card-title">
                @if(isset($asset))
                {{ __('home.edit_asset') }}
                @else
                {{ __('home.new_asset') }}
                @endif
            </h4>
            @include('layouts.partials.components.alert')
            <a href="{{route('asset.index')}}" class="btn btn-primary">{{ __('home.all_assets') }} </a>

        </div>
        <div class="card-body">
            <form action="{{isset($asset) ? route('asset.update', $asset->id) : route('asset.store')}}" method="POST" enctype="multipart/form-data">
                @csrf
                @if(isset($asset))
                @method('PUT')
                @else
                @method('POST')
                @endif

                <div class="form-row">
                    <div class="form-group col-md-6 mb-0">
                        <div class="form-group">
                            <label for=""> {{ __('home.name') }}</label>
                            <input type="text" class="form-control @error('name') {{'is-invalid'}} @enderror" id="name" placeholder=" name" name="name" value="{{isset($asset) ? $asset->name : old('name')}}" autocomplete="off">
                            @error('name')
                            <div id="" class="invalid-feedback">{{$message}}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group col-md-6 mb-0">
                        <div class="form-group">
                            <label for=""> {{ __('home.quantity') }}</label>
                            <input type="text" class="form-control @error('quantity') {{'is-invalid'}} @enderror" id="quantity" placeholder=" {{ __('home.quantity') }}" name="quantity" value="{{isset($asset) ? $asset->quantity : old('quantity')}}"  autocomplete="off">
                            @error('quantity')
                            <div id="" class="invalid-feedback">{{$message}}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group col-md-6 mb-0">
                        <div class="form-group">
                            <label for="validationServer01">{{ __('home.asset_value') }}</label>
                            <input type="text" class="form-control @error('asset_value') {{'is-invalid'}} @enderror" id="asset_value" name="asset_value" value="{{isset($asset) ? $asset->assets_value : old('assets_value')}}"  autocomplete="off">
                            @error('asset_value')
                            <div id="" class="invalid-feedback">{{$message}}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group col-md-6 mb-0">
                        <div class="form-group">
                            <label for="validationServer04">{{ __('home.account') }}

                            </label>
                            <select class="form-select form-control select2 @error('account_id') {{'is-invalid'}} @enderror" id="validationServer04" aria-describedby="validationServer04Feedback" required name="account_id">
                                <option selected disabled value="">Choose...</option>
                                @foreach($accounts as $account)
                                <option value="{{$account->id}}" @if(isset($asset)) @if($asset->account_id == $account->id) selected = 'selected' @endif @endif >{{$account->name}} - {{ $account->amount }}</option>
                                @endforeach
                            </select>
                            @error('account_id')
                            <div id="" class="invalid-feedback">{{$message}}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group col-md-6 mb-0">
                        <div class="form-group">
                            <label for="validationServer04">{{ __('home.category') }}
                            <a class="" data-bs-effect="effect-sign" data-bs-toggle="modal" href="#modaldemo8"><span class="badge bg-primary"> <i class="fe fe-plus "></i></span></a>
                            </label>
                            <select class="form-select form-control select2 @error('account') {{'is-invalid'}} @enderror" id="validationServer04" aria-describedby="validationServer04Feedback" required name="category_id">
                                <option selected disabled value="">Choose...</option>
                                @foreach($categories as $category)
                                <option value="{{$category->id}}" @if(isset($asset)) @if($asset->category_id == $category->id) selected = 'selected' @endif @endif >{{$category->name}}</option>
                                @endforeach
                            </select>
                            @error('category_id')
                            <div id="" class="invalid-feedback">{{$message}}</div>
                            @enderror
                        </div>
                    </div>

                    @if ($settings->date_type=='shamsi')
                    <div class="col-xl-6 mb-3">
                        <label for="validationServer01">{{ __('home.date') }}</label>
                        <input type="text" class="form-control form-control @error('date') {{'is-invalid'}} @enderror" name="shamsi_date" autocomplete="off" id="dates" value="{{isset($expense) ? $expense->date : old('date')}}">

                        @error('date')
                        <div id="" class="invalid-feedback">{{$message}}</div>
                        @enderror
                    </div>
                    @else
                    <div class="col-xl-6 mb-3">
                        <label for="validationServer01">{{ __('home.date') }}</label>
                        <input type="date" class="form-control @error('date') {{'is-invalid'}} @enderror" id="date" name="miladi_date" value="{{ isset($expense) ? $expense->miladi_date : date('Y-m-d') }}">
                        @error('date')
                        <div id="" class="invalid-feedback">{{$message}}</div>
                        @enderror
                    </div>
                    @endif
                    <div class="form-group col-md-12 mb-0">
                        <div class="form-group">
                            <label class="" for="inlineFormInputGroup">{{__('home.description')}}</label>
                            <textarea name="description" class="form-control" id="description">{{isset($asset) ? $asset->description: old('description')}}</textarea>

                            @error('description')
                            <span class="alert text-danger">{{$message}}</span>
                            @enderror
                        </div>
                        <div class="form-group col-md-12 mb-0">
                            <div class="form-footer mt-2">
                                <input type="submit" class="btn btn-primary" value="@if(isset($asset))
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
<div class="modal fade" id="modaldemo8">
    <div class="modal-dialog modal-dialog-centered text-center modal-sm" role="document">
        <div class="modal-content modal-content-demo">
            <div class="modal-header">
                <h6 class="modal-title">{{ __('home.new_category') }}</h6><button aria-label="Close" class="btn-close" data-bs-dismiss="modal"><span aria-hidden="true">&times;</span></button>
            </div>
            <form action="{{route('asset_category.store')}}" method="POST" enctype="multipart/form-data">
                <div class="modal-body">

                    @csrf

                    @method('POST')


                    <div class="form-row mb-3">

                        <div class="col-xl-12 mb-3">
                            <label for="validationServer01">{{ __('home.name') }}</label>
                            <input type="text" class="form-control @error('name') {{'is-invalid'}} @enderror" id="name" name="name" value="">
                            <input type="hidden" class="form-control" name="type" value="assets">
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

@section('pagescript')
<script>

</script>
@endsection

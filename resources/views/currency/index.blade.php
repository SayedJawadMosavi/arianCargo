@extends('layouts.app')
@section('title', 'All Currencies')

@section('content')

<div class="card mt-4">
    {{-- @dd(session()->has('success')) --}}
    @if (session()->has('success') || session()->has('error') )
        @include('layouts.partials.components.alert')
    @endif

    <div class="card-header ">
        <h3 class="card-title">{{ __('home.currencies') }}</h3>
        {{-- <a href="{{ route('currency.create') }}" class="btn btn-outline-primary mx-5">{{ __('home.new_currency') }}</a> --}}
    </div>

    <div class="card-body pt-4">
        <form action="{{isset($currency) ? route('currency.update', $currency) : route('currency.store')}}" method="POST" enctype="multipart/form-data">
            @csrf
            @if(isset($currency))
                @method('PUT')
            @else
                @method('POST')
            @endif

            <div class="form-row mb-3">
                @if(isset($currency))

                    <div class="col-xl-2 px-3 px-xl-1">
                        <label for="validationServer04">{{ __('home.status') }}</label>
                        <div class="form-group">
                            <label class="custom-switch form-switch mb-0">
                                <input type="checkbox" name="active" class="custom-switch-input" @if(isset($currency)) @if($currency->active == 1) {{'checked'}} @endif @else {{ 'checked' }}@endif>
                                <span class="custom-switch-indicator custom-switch-indicator-md"></span>
                                <span class="custom-switch-description">{{ __('home.active') }}</span>
                            </label>
                        </div>
                    </div>

                    <div class="col-xl-3 px-3 px-xl-1">
                        <label for="validationServer04">{{ __('home.default') }}</label>
                        <div class="form-group">

                            <label class="custom-switch form-switch mb-0">
                                <input type="checkbox" name="default" class="custom-switch-input" @if(isset($currency)) @if($currency->default == 1) {{'checked'}} @endif @else {{ 'checked' }}@endif>
                                <span class="custom-switch-indicator custom-switch-indicator-md"></span>
                                <span class="custom-switch-description">{{ __('home.yes') }}</span>
                            </label>
                        </div>
                    </div>

                @endif

                <div class="col-xl-6 mb-3">
                    <label for="validationServer01">{{ __('home.name') }}</label>
                    <input type="text" class="form-control @error('name') {{'is-invalid'}} @enderror" id="name"  name="name" value="{{isset($currency) ? $currency->name : old('name')}}">
                    @error('name')
                    <div id="" class="invalid-feedback">{{$message}}</div>
                    @enderror
                </div>

                <div class="col-xl-2 mb-3">
                    <label for="validationServer01"> &nbsp;</label>
                    <input type="submit" class="form-control btn btn-primary" value="@if(isset($currency))
                    {{ __('home.update') }}
                    @else
                        {{ __('home.save') }}
                    @endif">
                </div>
            </div>
        </form>

        <div class="table-responsive">
            <table id="data-table1" class="table table-bordered table-striped text-nowrap mb-0 table-hover">
                <thead>
                    <tr>
                        <th>{{ __('home.sn') }}</th>
                        <th>{{ __('home.name') }}</th>
                        <th>{{ __('home.default') }}</th>
                        <th>{{ __('home.active') }}</th>
                        <th>{{ __('home.action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($currencies as $obj)
                    <tr>
                        <td>{{$loop->iteration}}</td>
                        <td>{{$obj->name}}</td>
                        <td>
                            @if($obj->default ==1 )
                            <span class="tag tag-radius tag-round tag-primary">{{__('home.yes') }}</span>
                            @else
                            <span class="tag tag-radius tag-round tag-red">{{__('home.no') }}</span>
                            @endif
                        </td>
                        <td>
                            <div class="form-group">
                                <form action="{{route('currency.status', $obj->id )}}" class="update_{{ $obj->id }}" method="POST" class="d-inline">
                                    @method('post')
                                    @csrf
                                    <label class="custom-switch form-switch mb-0">
                                        <input type="checkbox" name="active" class="custom-switch-input" onchange="update({{ $obj->id }})" @if(isset($obj)) @if($obj->active == 1) {{'checked'}} @endif @else {{ 'checked' }}@endif>
                                        <span class="custom-switch-indicator custom-switch-indicator-md"></span>
                                        <span class="custom-switch-description">{{ __('home.active') }}</span>
                                    </label>
                                </form>

                            </div></td>
                        <td>
                            @can('currency.edit')
                            <a class="btn text-primary btn-sm" href="{{route('currency.edit', $obj)}}" data-bs-toggle="tooltip" data-bs-original-title="Edit"><span class="fe fe-edit fs-14"></span></a>
                            @endcan
                            @can('currency.delete')
                            <form action="{{route('currency.destroy', $obj)}}" method="POST" class="d-none">
                                @method('delete')
                                @csrf
                                <button type="submit" data-bs-toggle="tooltip"  data-bs-original-title="Delete" class="btn text-danger btn-sm"><span class="fe fe-trash-2 fs-14"></span></button>
                            </form>
                            @endcan
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    {{-- card-body --}}
</div>
@endsection

<script>
    function update($id){
        // alert('hi');
        $('.update_'+$id).submit();
    }



</script>

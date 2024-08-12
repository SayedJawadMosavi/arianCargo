@extends('layouts.app')
@section('title', 'All units')

@section('content')

<div class="card mt-4">
    {{-- @dd(session()->has('success')) --}}
    @if (session()->has('success') || session()->has('error') )
        @include('layouts.partials.components.alert')
    @endif

    <div class="card-header ">
        <h3 class="card-title">{{ __('home.units') }}</h3>
        {{-- <a href="{{ route('unit.create') }}" class="btn btn-outline-primary mx-5">{{ __('home.new_unit') }}</a> --}}
    </div>

    <div class="card-body pt-4">
        <form action="{{isset($unit) ? route('unit.update', $unit) : route('unit.store')}}" method="POST" enctype="multipart/form-data">
            @csrf
            @if(isset($unit))
                @method('PUT')
            @else
                @method('POST')
            @endif

            <div class="form-row mb-3">
                @if(isset($unit))

                    <div class="col-xl-2 px-3 px-xl-1">
                        <label for="validationServer04">{{ __('home.status') }}</label>
                        <div class="form-group">
                            <label class="custom-switch form-switch mb-0">
                                <input type="checkbox" name="active" class="custom-switch-input" @if(isset($unit)) @if($unit->active == 1) {{'checked'}} @endif @else {{ 'checked' }}@endif>
                                <span class="custom-switch-indicator custom-switch-indicator-md"></span>
                                <span class="custom-switch-description">{{ __('home.active') }}</span>
                            </label>
                        </div>
                    </div>

                @endif

                <div class="col-xl-4 mb-3">
                    <label for="validationServer01">{{ __('home.name') }}</label>
                    <input type="text" class="form-control @error('name') {{'is-invalid'}} @enderror" id="name"  name="name" value="{{isset($unit) ? $unit->name : old('name')}}">
                    @error('name')
                    <div id="" class="invalid-feedback">{{$message}}</div>
                    @enderror
                </div>
                <div class="col-xl-4 mb-3">
                    <label for="validationServer01">{{ __('home.short_name') }}</label>
                    <input type="text" class="form-control @error('short_name') {{'is-invalid'}} @enderror" id="short_name"  name="short_name" value="{{isset($unit) ? $unit->short_name : old('short_name')}}">
                    @error('short_name')
                    <div id="" class="invalid-feedback">{{$message}}</div>
                    @enderror
                </div>

                <div class="col-xl-2 mb-3">
                    <label for="validationServer01"> &nbsp;</label>
                    <input type="submit" class="form-control btn btn-primary" value="@if(isset($unit))
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
                        <th>{{ __('home.short_name') }}</th>
                        <th>{{ __('home.active') }}</th>
                        <th>{{ __('home.action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($units as $obj)
                    <tr>
                        <td>{{$obj->id}}</td>
                        <td>{{$obj->name}}</td>
                        <td>{{$obj->short_name}}</td>

                        <td>
                            <div class="form-group">
                                <form action="{{route('unit.status', $obj->id )}}" class="update_{{ $obj->id }}" method="POST" class="d-inline">
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
                            <a class="btn text-primary btn-sm" href="{{route('unit.edit', $obj)}}" data-bs-toggle="tooltip" data-bs-original-title="Edit"><span class="fe fe-edit fs-14"></span></a>
                            <form action="{{route('unit.destroy', $obj)}}" method="POST" class=" d-none">
                                @method('delete')
                                @csrf
                                <button type="submit" data-bs-toggle="tooltip"  data-bs-original-title="Delete" class="btn text-danger btn-sm"><span class="fe fe-trash-2 fs-14"></span></button>
                            </form>
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

@extends('layouts.app')

@section('title', 'New permissions')
@section('content')

<div class="card mt-4">
    <div class="card-header d-flex justify-content-between">
        <h4 class="card-title">
            @if(isset($permissions))
                {{ __('home.edit_permission') }}
            @else
            {{ __('home.new_permission') }}
            @endif
        </h4>
        <a href="{{route('permissions.index')}}" class="btn btn-primary">{{ __('home.all_permissions') }}</a>
        </div>
        <div class="card-body">
            <form action="{{ isset($permission) ? route('permissions.update', $permission) : route('permissions.store') }}" method="post" enctype="multipart/form-data">
            @if (count($errors) > 0)
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
            @csrf
            @if(isset($permission))
            @method('PUT')
            @else
            @method('POST')
            @endif
            <div class="form-row align-items-center">
                <div class="col-6 col-sm-3">
                    <label class="" for="inlineFormInput">{{ __('home.permission_code') }}</label>
                    <input type="text" class="form-control " id="inlineFormInput" placeholder="Example :abc.create" name="name" value="{{isset($permission) ? $permission->name: old('name')}}" {{isset($permission) ? 'readonly': ''}}>
                    @error('name')
                    <span class="alert text-danger">{{$message}}</span>
                    @enderror
                </div>
                <div class="col-6 col-sm-3">
                    <label class="" for="inlineFormInput">{{ __('home.name') }}</label>
                    <input type="text" class="form-control " id="inlineFormInput" placeholder="Example : Create Role" name="display_name" value="{{isset($permission) ? $permission->display_name: old('display_name')}}">
                    @error('display_name')
                    <span class="alert text-danger">{{$message}}</span>
                    @enderror
                </div>
                <div class="col-6 col-sm-3">
                    <label class="" for="inlineFormInput">{{ __('home.group') }}</label>
                    <select class="form-control " name="group" id="group">
                        <option value="" disabled selected>Select Group</option>
                        @foreach ($Permissions_group as $obj)


                        <option value="{{$obj->id}}" @if(isset($permission)) @if($permission->role ==$obj->name) selected = 'selected' @endif @endif >{{$obj->name}}</option>

                        @endforeach
                    </select>
                    @error('role')
                    <span class="alert text-danger">{{$message}}</span>
                    @enderror
                </div>
                <div class="col-12 col-sm-2">
                    <label class="" for="inlineFormInputGroup"><br></label>
                    <button type="submit" class="btn btn-block btn-primary text-center">
                        @if(isset($permission))
                        {{ __('home.update') }}
                    @else
                        {{ __('home.save') }}
                    @endif</button>
                </div>
            </div>
        </form>

    </div>
</div>

@endsection

@section('pagescript')
<script>

</script>
@endsection

@extends('layouts.app')

@section('title', 'New users')
@section('content')

<div class="card mt-4">
    <div class="card-header d-flex justify-content-between">
        <h4 class="card-title">
            @if(isset($users))
            {{ __('home.edit_user') }}
            @else
            {{ __('home.new_user') }}
            @endif
        </h4>
        <a href="{{route('users.index')}}" class="btn btn-primary">{{ __('home.all_users') }} </a>

    </div>
    <div class="card-body">
        <form action="{{isset($users) ? route('users.update', $users->id) : route('users.store')}}" method="POST" enctype="multipart/form-data">
            @csrf
            @if(isset($users))
            @method('PUT')
            @else
            @method('POST')
            @endif

            <div class="form-row">
                <div class="form-group col-md-6 mb-0">
                    <div class="form-group">
                        <label for=""> {{ __('home.name') }}</label>
                        <input type="text" class="form-control @error('name') {{'is-invalid'}} @enderror" id="name" placeholder=" name" name="name" value="{{isset($users) ? $users->name : old('name')}}">
                        @error('name')
                        <div id="" class="invalid-feedback">{{$message}}</div>
                        @enderror
                    </div>
                </div>
                <div class="form-group col-md-6 mb-0">
                    <div class="form-group">
                        <label for=""> {{ __('home.email') }}</label>
                        <input type="email" class="form-control @error('email') {{'is-invalid'}} @enderror" id="email" placeholder="Email" name="email" value="{{isset($users) ? $users->email : old('email')}}">
                        @error('email')
                        <div id="" class="invalid-feedback">{{$message}}</div>
                        @enderror
                    </div>
                </div>
                <div class="form-group col-md-6 mb-0">
                    <div class="form-group">
                        <label for=""> {{ __('home.password') }}</label>
                        <input type="password" placeholder="Password" class="form-control " name="password"  autocomplete="off">
                        @error('password')
                        <span class="alert text-danger">{{$message}}</span>
                        @enderror
                    </div>
                </div>
                <div class="form-group col-md-6 mb-0">
                    <div class="form-group">
                        <label for=""> {{ __('home.confirm_password') }}</label>
                        <input type="password" class="form-control " name="confirm_password" autocomplete="off">

                        @error('confirm_password')
                        <span class="alert text-danger">{{$message}}</span>
                        @enderror
                    </div>
                </div>
                <div class="form-group col-md-6 mb-0">
                    <div class="form-group">
                        <label for=""> {{ __('home.role') }}</label>

                        <select class="form-control " name="roles" id="roles">
                            <option value="" disabled selected>Select Role</option>
                            @foreach ($roles as $role)
                            <option value="{{ $role->id }}" @if(isset($users) && $users->roles->pluck('id')->contains($role->id)) selected @endif>{{ $role->name }}</option>
                            @endforeach
                        </select>
                        @error('role')
                        <span class="alert text-danger">{{$message}}</span>
                        @enderror
                    </div>
                </div>
                <div class="form-group col-md-6 mb-0">
                    <div class="form-group">
                        <label for=""> {{ __('home.branch') }}</label>

                        <select class="form-control " name="branch_id" id="branch_id">
                            <option value="" disabled selected>Select branch</option>
                            @foreach ($branches as $obj)
                                <option value="{{$obj->id}}" @if(isset($users)) @if($users->branch_id ==$obj->id) selected = 'selected' @endif @endif >{{$obj->name}}</option>
                            @endforeach
                        </select>
                        @error('role')
                        <span class="alert text-danger">{{$message}}</span>
                        @enderror
                    </div>
                </div>
                <div class="form-group col-md-6 mb-0">
                    <div class="form-group">
                        <label for=""> {{ __('home.photo') }}</label>
                        <input id="" type="file" name="photo" accept=".jpg, .png, image/jpeg, image/png" class=" form-control @error('description') {{'is-invalid'}} @enderror" id="inpFile" onchange="$('.image-preview__image')[0].src = window.URL.createObjectURL(this.files[0]);">
                        @error('photo')
                        <div id="" class="invalid-feedback">{{$message}}</div>
                        @enderror
                    </div>
                    <img src="/{{isset($user) ? $user->image : old('image')}}" class="image-preview__image" width="200px" /> <!--for preview purpose -->

                </div>
            </div>

            <div class="form-footer mt-2">
                <input type="submit" class="btn btn-primary" value="@if(isset($users))
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
<script>

</script>
@endsection

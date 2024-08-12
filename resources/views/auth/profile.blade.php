@extends('layouts.app')

@section('title', 'Profile')
@section('content')

<div class="card">
    @include('layouts.partials.components.alert')

    <div class="card-header">
        <h4 class="card-title">
            Change Password
        </h4>

    </div>
    <div class="card-body">
        <form action="{{route('profile.post', $user)}}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('POST')

            <div class="form-row mb-3">
                <div class="form-group col-md-4 mb-0">
                    <input type="hidden" class="form-control" name="user" value="{{$user->id}}">

                    <input type="password" class="form-control @error('currentPassword') {{'is-invalid'}} @enderror" id="currentPassword" placeholder="Current Password" name="currentPassword">
                    @error('currentPassword')
                        <div id="" class="invalid-feedback">{{$message}}</div>
                    @enderror
                </div>
                <div class="form-group col-md-4 mb-0">
                    <input type="password" class="form-control @error('password') {{'is-invalid'}} @enderror" id="password" placeholder="password" name="password">
                    @error('password')
                        <div id="" class="invalid-feedback">{{$message}}</div>
                    @enderror
                </div>
                <div class="form-group col-md-4 mb-0">
                    <input type="password" class="form-control @error('password_confirmation') {{'is-invalid'}} @enderror" placeholder="Password Confirmation" name="password_confirmation">
                    @error('password_confirmation')
                        <div id="" class="invalid-feedback">{{$message}}</div>
                    @enderror
                </div>
            </div>

            <div class="form-footer mt-2">
                <input type="submit" class="btn btn-primary" value="Publish">
            </div>
        </form>

    </div>
</div>
@endsection

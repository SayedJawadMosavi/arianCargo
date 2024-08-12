@extends('layouts.app')

@section('title', 'New roles')
@section('content')

<div class="card  mt-4">
    <div class="card-header d-flex justify-content-between">
        <h4 class="card-title">
            @if(isset($roles))
                {{ __('home.edit_role') }}
            @else
                {{ __('home.new_role') }}
            @endif
        </h4>
        @can('role.create')
        <a href="{{route('roles.index')}}" class="btn btn-primary">{{ __('home.all_roles') }}</a>
        @endcan
    </div>

    <div class="row m-2 mb-1">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 jumbotron shade pt-1">
            <div class="container mt-4">

                @if (count($errors) > 0)
                <div class="alert alert-danger">
                    <strong>Whoops!</strong> There were some problems with your input.<br><br>
                    <ul>
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <form method="POST" action="{{ route('roles.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label"> {{ __('home.name') }} </label>
                        <input value="{{ old('name') }}" type="text" class="form-control" name="name" placeholder="Name" required>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <label for="select_all"><strong>{{ __('home.select_all') }}</strong></label>
                            <input type="checkbox" id="select_all" name="all_permission">
                            <br />
                        </div>
                        @foreach($Permissions as $value)
                        <div class="col-md-4">
                            <div class="card bg-light">
                                <h5 class="card-header bg-transparent border-bottom mt-0">
                                    <input type="checkbox" class="parent_main" id="{{$value->name}}_parent" onclick="check('{{ $value->name }}')">
                                    <label for="{{$value->name}}_parent">
                                        {{ __("permissions.{$value->name}") }}
                                    </label>
                                </h5>
                                <div class="card-body">
                                    <ul id="{{$value->name}}" class="test-sub-main">
                                        @foreach($value->permissions as $key)
                                        <li>
                                            <input type="checkbox" name="permission[{{ $key->name }}]" id="{{$value->name}}_{{$key->name}}" value="{{ $key->name }}" class='permission'>
                                            {{ __("permissions.{$key->name}") }}
                                        </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <button type="submit" class="btn btn-primary">
                        @if(isset($users))
                            {{ __('home.update') }}
                        @else
                            {{ __('home.save') }}
                        @endif
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('pagescript')
<script type="text/javascript">
    $(document).ready(function() {
        $('#select_all').on('click', function() {
            let checked = $(this).is(':checked');
            $('.permission, .parent_main').prop('checked', checked);
        });
    });

    function check(parent) {
        let isChecked = document.getElementById(`${parent}_parent`).checked;
        $(`#${parent} .permission`).prop('checked', isChecked);
    }
</script>
@endsection

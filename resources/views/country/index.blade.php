@extends('layouts.app')
@section('title', 'All Country')

@section('content')

<div class="card mt-4">
    {{-- @dd(session()->has('success')) --}}
    @if (session()->has('success') || session()->has('error') )
    @include('layouts.partials.components.alert')
    @endif

    <div class="card-header ">
        <h3 class="card-title">{{ __('home.country') }}</h3>
        {{-- <a href="{{ route('country.create') }}" class="btn btn-outline-primary mx-5">{{ __('home.new_country') }}</a> --}}
    </div>

    <div class="card-body pt-4">
        <form action="{{isset($country) ? route('country.update', $country) : route('country.store')}}" method="POST" enctype="multipart/form-data">
            @csrf
            @if(isset($country))
            @method('PUT')
            @else
            @method('POST')
            @endif

            <div class="form-row mb-3">
                @if(isset($country))

                <div class="col-xl-2 px-3 px-xl-1">
                    <label for="validationServer04">{{ __('home.status') }}</label>
                    <div class="form-group">
                        <label class="custom-switch form-switch mb-0">
                            <input type="checkbox" name="active" class="custom-switch-input" @if(isset($country)) @if($country->active == 1) {{'checked'}} @endif @else {{ 'checked' }}@endif>
                            <span class="custom-switch-indicator custom-switch-indicator-md"></span>
                            <span class="custom-switch-description">{{ __('home.active') }}</span>
                        </label>
                    </div>
                </div>

                @endif

                <div class="col-xl-4 mb-3">
                    <label for="validationServer01">{{ __('home.name') }}</label>
                    <input type="text" class="form-control @error('name') {{'is-invalid'}} @enderror" id="name" name="name" value="{{isset($country) ? $country->name : old('name')}}">
                    @error('name')
                    <div id="" class="invalid-feedback">{{$message}}</div>
                    @enderror
                </div>
                <div class="col-xl-4 mb-3">
                    <label for="validationServer01">{{ __('home.description') }}</label>
                    <input type="text" class="form-control @error('description') {{'is-invalid'}} @enderror" id="description" name="description" value="{{isset($country) ? $country->description : old('short_name')}}">
                    @error('short_name')
                    <div id="" class="invalid-feedback">{{$message}}</div>
                    @enderror
                </div>
            </div>

            <div class="form-row mb-3">


                <div class="col-sm-12 mt-3">
                    <h4>{{__('home.price')}}</h4>
                    <hr>
                </div>
                <div class="col-sm-12">
                    <div class="table-responsive">
                        <span id="result"></span>
                        <table class="table table-bordered table-striped" id="user_table">
                            <thead>
                                <tr>
                                    <th scope="col"> {{__('home.kg')}}</th>
                                    <th scope="col"> {{__('home.price')}}</th>

                                    <th> {{__('home.action')}}</th>
                                </tr>
                            </thead>
                            <tbody id="tbody">

                            </tbody>

                        </table>
                    </div>
                </div>
                <div class="form-footer mt-2">
                    <input type="submit" class="btn btn-primary" value="@if(isset($shareholder))
                    {{ __('home.update') }}
                @else
                    {{ __('home.save') }}
                @endif">
                </div>
        </form>

        <div class="table-responsive mt-2">
            <table id="data-table1" class="table table-bordered table-striped text-nowrap mb-0 table-hover">
                <thead>
                    <tr>
                        <th>{{ __('home.sn') }}</th>
                        <th>{{ __('home.name') }}</th>
                        <th>{{ __('home.description') }}</th>
                        <th>{{ __('home.rate') }}</th>
                        <th>{{ __('home.active') }}</th>
                        <th>{{ __('home.action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($countries as $obj)
                    <tr>
                        <td>{{$obj->id}}</td>
                        <td>{{$obj->name}}</td>
                        <td>{{$obj->description}}</td>
                        <td>{{$obj->rate}}</td>

                        <td>
                            <div class="form-group">
                                <form action="{{route('country.status', $obj->id )}}" class="update_{{ $obj->id }}" method="POST" class="d-inline">
                                    @method('post')
                                    @csrf
                                    <label class="custom-switch form-switch mb-0">
                                        <input type="checkbox" name="active" class="custom-switch-input" onchange="update({{ $obj->id }})" @if(isset($obj)) @if($obj->active == 1) {{'checked'}} @endif @else {{ 'checked' }}@endif>
                                        <span class="custom-switch-indicator custom-switch-indicator-md"></span>
                                        <span class="custom-switch-description">{{ __('home.active') }}</span>
                                    </label>
                                </form>

                            </div>
                        </td>
                        <td>
                            <a class="btn btn-outline-success btn-sm rounded-0" href="{{ route('country.show', $obj) }}" data-bs-toggle="tooltip" data-bs-original-title="{{ __('home.detail') }}">
                                <span class="fe fe-eye fs-16"></span>
                            </a>
                            <a class="btn text-primary btn-sm" href="{{route('country.edit', $obj)}}" data-bs-toggle="tooltip" data-bs-original-title="Edit"><span class="fe fe-edit fs-14"></span></a>
                            <form action="{{route('country.destroy', $obj)}}" method="POST" class=" d-none">
                                @method('delete')
                                @csrf
                                <button type="submit" data-bs-toggle="tooltip" data-bs-original-title="Delete" class="btn text-danger btn-sm"><span class="fe fe-trash-2 fs-14"></span></button>
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
@section('pagescript')

<script>
    function update($id) {
        // alert('hi');
        $('.update_' + $id).submit();
    }

    $(document).ready(function() {

        var count = 1;

        dynamic_field(count);

        function dynamic_field(number) {
            html = '<tr>';
            html += '<td> <input type="text"  class="form-control " name="kg[]" id="kg" >' +
                '@error("kg")'
            '<span class="alert text-danger">' + '{{$message}}' + '</span>' +
            '@enderror' +
            '</td>'

            html += '<td> <input type="number" step="0.0001" class="form-control " name="price[]" id="price" >' +
                '@error("price")'
            '<span class="alert text-danger">' + '{{$message}}' + '</span>' +
            '@enderror' +
            '</td>'

            if (number > 1) {
                html += '<td><button type="button" name="remove" id="" class="btn btn-danger remove"> <i class="fa fa-minus"></i></button></td></tr>';
                $('#tbody').append(html);
            } else {
                html += '<td><button type="button" name="add" id="add" class="btn btn-success btn btn-primary"> <i class="fa fa-plus"></i></button></td></tr>';
                $('#tbody').html(html);
            }
        }

        $(document).on('click', '#add', function() {
            count++;
            dynamic_field(count);
        });

        $(document).on('click', '.remove', function() {
            count--;
            $(this).closest("tr").remove();
        });

    });
</script>
@endsection

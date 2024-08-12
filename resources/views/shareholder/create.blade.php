@extends('layouts.app')

@section('title', 'New ShareHolder')
@section('content')

    <div class="card mt-4">

        @if (session()->has('success') || session()->has('error') )
            @include('layouts.partials.components.alert')
        @endif
        <div class="card-header d-flex justify-content-between">
            <h4 class="card-title">
                @if(isset($shareholder))
                    {{ __('home.edit_shareholder') }}
                @else
                    {{ __('home.new_shareholder') }}
                @endif
            </h4>
            <a href="{{route('shareholder.index')}}" class="btn btn-primary">{{ __('home.all_shareholders') }}</a>

        </div>
        <div class="card-body ">
            <form action="{{isset($shareholder) ? route('shareholder.update', $shareholder) : route('shareholder.store')}}" method="POST" enctype="multipart/form-data">
                @csrf
                @if(isset($shareholder))
                    @method('PUT')
                @else
                    @method('POST')
                @endif

                <div class="form-row mb-3">
                    @if(isset($shareholder))

                    <div class="col-xl-2 col-sm-2 px-3 px-xl-1">
                        <label for="validationServer04">{{ __('home.status') }}</label>
                        <div class="form-group">
                            <label class="custom-switch form-switch mb-0">
                                <input type="checkbox" name="active" class="custom-switch-input" @if(isset($shareholder)) @if($shareholder->active == 1) {{'checked'}} @endif @else {{ 'checked' }}@endif>
                                <span class="custom-switch-indicator custom-switch-indicator-md"></span>
                                <span class="custom-switch-description">{{ __('home.active') }}</span>
                            </label>
                        </div>
                    </div>
                    @endif


                    <div class="col-xl-3 col-sm-4 mb-3">
                        <label for="validationServer01">{{ __('home.name') }}</label>
                        <input type="text" class="form-control @error('name') {{'is-invalid'}} @enderror" id="name"  name="name" value="{{isset($shareholder) ? $shareholder->name : old('name')}}" autocomplete="off">
                        @error('name')
                        <div id="" class="invalid-feedback">{{$message}}</div>
                        @enderror
                    </div>

                    <div class="col-xl-3 col-sm-4 mb-3">
                        <label for="validationServer01">{{ __('home.percentage') }}</label>
                        <input type="number" class="form-control @error('percentage') {{'is-invalid'}} @enderror" id="percentage"  name="percentage" value="{{isset($shareholder) ? $shareholder->percentage : old('percentage', 0)}}" autocomplete="off">
                        @error('percentage')
                        <div id="" class="invalid-feedback">{{$message}}</div>
                        @enderror
                    </div>
                    <div class="col-xl-3 col-sm-4 mb-3">
                        <label for="validationServer01">{{ __('home.mobile') }}</label>
                        <input type="number" class="form-control @error('mobile') {{'is-invalid'}} @enderror" id="mobile"  name="mobile" value="{{isset($shareholder) ? $shareholder->mobile : old('mobile')}}" autocomplete="off">
                        @error('mobile')
                        <div id="" class="invalid-feedback">{{$message}}</div>
                        @enderror
                    </div>
                    <div class="col-xl-3 col-sm-4 mb-3">
                        <label for="validationServer01">{{ __('home.nid') }}</label>
                        <input type="text" class="form-control @error('nid') {{'is-invalid'}} @enderror" id="nid"  name="nid" value="{{isset($shareholder) ? $shareholder->nid : old('nid')}}">
                        @error('nid')
                        <div id="" class="invalid-feedback">{{$message}}</div>
                        @enderror
                    </div>
                    <div class="col-xl-8 col-sm-8 mb-3">
                        <label for="validationServer01">{{ __('home.address') }}</label>
                        <input type="text" class="form-control @error('address') {{'is-invalid'}} @enderror" id="address"  name="address" value="{{isset($shareholder) ? $shareholder->address : old('address')}}">
                        @error('address')
                        <div id="" class="invalid-feedback">{{$message}}</div>
                        @enderror
                    </div>
                    <div class="col-12 col-sm-12">
                        <label class="" for="inlineFormInputGroup">{{__('home.description')}}</label>
                        <textarea name="description" class="form-control" id="description">{{isset($shareholder) ? $shareholder->description: old('description')}}</textarea>
                        @error('description')
                        <span class="alert text-danger">{{$message}}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-row mb-3">

                    @if(!isset($shareholder) )
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
                                        <th width="35%">{{__('home.account')}}</th>
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

                </div>

                <div class="form-footer mt-2">
                    <input type="submit" class="btn btn-primary" value="@if(isset($shareholder))
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
    $(document).ready(function() {

        var count = 1;

        dynamic_field(count);

        function dynamic_field(number) {
            html = '<tr>';
            html += "<td>" +
                '<select class="form-control jstreasury"  name="account[]" id="to_currency">' +
                +"@foreach ($accounts as $obj)" +
                "<option value='{{$obj->id}}' >" + '{{$obj->name}} ' + ' {{$obj->currency->name}} ' + '({{$obj->amount}})' + "</option>" +
                "@endforeach" +
                "</select> " +
                "</td>"
            html += '<td><input type="number" name="amount[]" class="form-control jsamount" value="0" /></td>';
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

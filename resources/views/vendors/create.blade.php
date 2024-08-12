@extends('layouts.app')

@section('title', 'Vendors')
@section('content')

    <div class="card mt-4">
        @if (session()->has('success') || session()->has('error') )
            @include('layouts.partials.components.alert')
        @endif


        <div class="card-header d-flex justify-content-between">
            <h4 class="card-title">
                @if(isset($vendor))
                    {{ __('home.edit_vendor') }}
                @else
                    {{ __('home.new_vendor') }}
                @endif
            </h4>
            <a href="{{route('vendors.index')}}" class="btn btn-primary">{{ __('home.all_vendors') }}</a>

        </div>
        <div class="card-body ">
            <form action="{{isset($vendor) ? route('vendors.update', $vendor) : route('vendors.store')}}" method="POST" enctype="multipart/form-data">
                @csrf
                @if(isset($vendor))
                    @method('PUT')
                @else
                    @method('POST')
                @endif

                <div class="form-row mb-3">
                    @if(isset($vendor))

                    <div class="col-xl-4 col-sm-2 px-3 px-xl-1">
                        <label for="validationServer04">{{ __('home.status') }}</label>
                        <div class="form-group">
                            <label class="custom-switch form-switch mb-0">
                                <input type="checkbox" name="active" class="custom-switch-input" @if(isset($vendor)) @if($vendor->active == 1) {{'checked'}} @endif @else {{ 'checked' }}@endif>
                                <span class="custom-switch-indicator custom-switch-indicator-md"></span>
                                <span class="custom-switch-description">{{ __('home.active') }}</span>
                            </label>
                        </div>
                    </div>
                    @endif


                    <div class="col-xl-4 col-sm-4 mb-3">
                        <label for="validationServer01">{{ __('home.company') }}</label>
                        <input type="text" class="form-control @error('company') {{'is-invalid'}} @enderror" id="company"  name="company" value="{{isset($vendor) ? $vendor->company : old('company')}}" autocomplete="off">
                        @error('company')
                        <div id="" class="invalid-feedback">{{$message}}</div>
                        @enderror
                    </div>
                    <div class="col-xl-4 col-sm-4 mb-3">
                        <label for="validationServer01">{{ __('home.contact_person') }}</label>
                        <input type="text" class="form-control @error('contact_person') {{'is-invalid'}} @enderror" id="contact_person"  name="contact_person" value="{{isset($vendor) ? $vendor->contact_person : old('contact_person')}}" autocomplete="off">
                        @error('contact_person')
                        <div id="" class="invalid-feedback">{{$message}}</div>
                        @enderror
                    </div>

                    <div class="col-xl-4 col-sm-4 mb-3">
                        <label for="validationServer01">{{ __('home.mobile') }}</label>
                        <input type="text" class="form-control @error('mobile') {{'is-invalid'}} @enderror" id="mobile"  name="mobile" value="{{isset($vendor) ? $vendor->mobile : old('mobile')}}" autocomplete="off">
                        @error('mobile')
                        <div id="" class="invalid-feedback">{{$message}}</div>
                        @enderror
                    </div>
                    <div class="col-xl-4 col-sm-4 mb-3">
                        <label for="validationServer01">{{ __('home.license') }}</label>
                        <input type="text" class="form-control @error('license') {{'is-invalid'}} @enderror" id="license"  name="license" value="{{isset($vendor) ? $vendor->license : old('license')}}" autocomplete="off">
                        @error('license')
                        <div id="" class="invalid-feedback">{{$message}}</div>
                        @enderror
                    </div>
                    <div class="col-xl-8 col-sm-12 mb-3">
                        <label for="validationServer01">{{ __('home.address') }}</label>
                        <input type="text" class="form-control @error('address') {{'is-invalid'}} @enderror" id="address"  name="address" value="{{isset($vendor) ? $vendor->address : old('address')}}">
                        @error('address')
                        <div id="" class="invalid-feedback">{{$message}}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-row mb-3 d-none">

                    @if(!isset($vendor) )
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
                                        <th width="35%">{{__('home.currency')}}</th>
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
                    <input type="submit" class="btn btn-primary" value="@if(isset($vendor))
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
                '<select class="form-control jstreasury"  name="treasury[]" id="to_currency">' +
                +"@foreach ($currencies as $obj)" +
                "<option value='{{$obj->id}}' >" + '{{$obj->name}}' + "</option>" +
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

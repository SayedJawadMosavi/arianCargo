@extends('layouts.app')

@section('title', 'New Staff')
@section('content')

<div class="card mt-4">
    <div class="card-header d-flex justify-content-between">
        <h4 class="card-title">
            @if(isset($staff))
            {{ __('home.edit_staff') }}
            @else
            {{ __('home.new_staff') }}
            @endif
        </h4>
        <a href="{{route('staff.index')}}" class="btn btn-primary">{{ __('home.all_staff') }}</a>

    </div>
    <div class="card-body ">
        <form action="{{isset($staff) ? route('staff.update', $staff) : route('staff.store')}}" method="POST" enctype="multipart/form-data">
            @csrf
            @if(isset($staff))
            @method('PUT')
            @else
            @method('POST')
            @endif

            <div class="form-row mb-3">
                @if(isset($staff))

                <div class="col-xl-2 col-sm-2 px-3 px-xl-1">
                    <label for="validationServer04">{{ __('home.status') }}</label>
                    <div class="form-group">
                        <label class="custom-switch form-switch mb-0">
                            <input type="checkbox" name="active" class="custom-switch-input" @if(isset($staff)) @if($staff->active == 1) {{'checked'}} @endif @else {{ 'checked' }}@endif>
                            <span class="custom-switch-indicator custom-switch-indicator-md"></span>
                            <span class="custom-switch-description">{{ __('home.active') }}</span>
                        </label>
                    </div>
                </div>
                @endif


                <div class="col-xl-4 col-sm-4 mb-3">
                    <label for="validationServer01">{{ __('home.name') }}</label>
                    <input type="text" class="form-control @error('name') {{'is-invalid'}} @enderror" id="name" name="name" value="{{isset($staff) ? $staff->name : old('name')}}" autocomplete="off">
                    @error('name')
                    <div id="" class="invalid-feedback">{{$message}}</div>
                    @enderror
                </div>
                <div class="col-xl-4 col-sm-4 mb-3">
                    <label for="validationServer01">{{ __('home.fatherName') }}</label>
                    <input type="text" class="form-control @error('father_name') {{'is-invalid'}} @enderror" id="father_name" name="father_name" value="{{isset($staff) ? $staff->fathername : old('fathername')}}" autocomplete="off">
                    @error('father_name')
                    <div id="" class="invalid-feedback">{{$message}}</div>
                    @enderror
                </div>
                @if ($settings->date_type=='shamsi')
                <div class="col-xl-4 mb-3">
                    <label for="validationServer01">{{ __('home.dob') }}</label>
                    <input type="text" class="form-control form-control @error('shamsi_date_dob') {{'is-invalid'}} @enderror" name="shamsi_date_dob" autocomplete="off" id="shamsi_date_dob" value="{{isset($staff) ? $staff->shamsi_date_dob : old('shamsi_date_dob')}}">

                    @error('shamsi_date_dob')
                    <div id="" class="invalid-feedback">{{$message}}</div>
                    @enderror
                </div>
                @else
                <div class="col-xl-4 mb-3">
                    <label for="validationServer01">{{ __('home.dob') }}</label>
                    <input type="date" class="form-control @error('miladi_dob') {{'is-invalid'}} @enderror" id="miladi_dob" name="miladi_dob" value="{{ isset($staff) ? $staff->miladi_dob : old('Y-m-d') }}">
                    @error('miladi_dob')
                    <div id="" class="invalid-feedback">{{$message}}</div>
                    @enderror
                </div>
                @endif
                @if ($settings->date_type=='shamsi')
                <div class="col-xl-3 mb-3">
                    <label for="validationServer01">{{ __('home.joining_date') }}</label>
                    <input type="text" class="form-control form-control @error('joining_date_shamsi') {{'is-invalid'}} @enderror" name="joining_date_shamsi" autocomplete="off" id="joining_date_shamsi" value="{{isset($staff) ? $staff->joining_date_shamsi : old('joining_date_shamsi')}}">

                    @error('joining_date_shamsi')
                    <div id="" class="invalid-feedback">{{$message}}</div>
                    @enderror
                </div>
                @else
                <div class="col-xl-3 mb-3">
                    <label for="validationServer01">{{ __('home.joining_date') }}</label>
                    <input type="date" class="form-control @error('joining_date_miladi') {{'is-invalid'}} @enderror" id="joining_date_miladi" name="joining_date_miladi" value="{{ isset($staff) ? $staff->joining_date_miladi : old('Y-m-d') }}">
                    @error('joining_date_miladi')
                    <div id="" class="invalid-feedback">{{$message}}</div>
                    @enderror
                </div>
                @endif

                <div class="col-xl-3 col-sm-3 mb-3">
                    <label for="validationServer01">{{ __('home.mobile') }}</label>
                    <input type="text" class="form-control @error('mobile') {{'is-invalid'}} @enderror" id="mobile" name="mobile" value="{{isset($staff) ? $staff->mobile : old('mobile')}}">
                    @error('mobile')
                    <div id="" class="invalid-feedback">{{$message}}</div>
                    @enderror
                </div>
                <div class="col-xl-3 col-sm-3 mb-3">
                    <label for="validationServer01">{{ __('home.position') }}</label>
                    <input type="text" class="form-control @error('position') {{'is-invalid'}} @enderror" id="position" name="position" value="{{isset($staff) ? $staff->position : old('position')}}">
                    @error('position')
                    <div id="" class="invalid-feedback">{{$message}}</div>
                    @enderror
                </div>
                <div class="col-xl-3 col-sm-3 mb-3">
                    <label for="validationServer01">{{ __('home.nid') }}</label>
                    <input type="text" class="form-control @error('nid') {{'is-invalid'}} @enderror" id="nid" name="nid" value="{{isset($staff) ? $staff->tazkira_number : old('tazkira_number')}}">
                    @error('nid')
                    <div id="" class="invalid-feedback">{{$message}}</div>
                    @enderror
                </div>
                <div class="col-xl-4 col-sm-4 mb-3">
                <label class="" for="inlineFormInputGroup">{{__('home.docs')}}</label>
                        <input type="file" class="form-control "  name="file[]" multiple="multiple">
                        @error('file')
                        <span class="alert text-danger">{{$message}}</span>
                        @enderror
                </div>
                <div class="col-xl-4 col-sm-4 mb-3">
                    <label class="" for="inlineFormInputGroup">{{__('home.education')}}</label>
                    <select class="form-control " name="education" id="education">
                        <option value="Twelve degree" @if (isset($staff)) @if($staff->education=='Twelve degree')
                            selected="selected" @endif @endif>{{__('home.twelve_degree')}}</option>
                        <option value="bachelor degree" @if (isset($staff)) @if($staff->education=='bachelor degree')
                            selected="selected" @endif @endif>{{__('home.bachelor_degree')}}</option>
                        <option value="master degree" @if (isset($staff)) @if($staff->education=='master degree')
                            selected="selected" @endif @endif>{{__('home.master_degree')}}</option>
                        <option value="Ph.D" @if (isset($staff)) @if($staff->education=='Ph.D')
                            selected="selected" @endif @endif>{{__('home.phd')}}</option>
                    </select>
                    @error('type')
                    <span class="alert text-danger">{{$message}}</span>
                    @enderror
                </div>
                <div class="col-xl-4 col-sm-4 mb-3">
                    <label class="" for="salary">{{__('home.salary')}}</label>
                    <input type="number" step="0.1" class="form-control " id="salary" name="salary" value="{{isset($staff) ? $staff->salary: old('salary', 0)}}">
                    @error('salary')
                    <span class="alert text-danger">{{$message}}</span>
                    @enderror
                </div>
                <div class="col-xl-12 col-sm-12 mb-3">
                    <label for="validationServer01">{{ __('home.address') }}</label>
                    <input type="text" class="form-control @error('address') {{'is-invalid'}} @enderror" id="address" name="address" value="{{isset($staff) ? $staff->address : old('address')}}">
                    @error('address')
                    <div id="" class="invalid-feedback">{{$message}}</div>
                    @enderror
                </div>
            </div>



            <div class="form-footer mt-2">
                <input type="submit" class="btn btn-primary" value="@if(isset($staff))
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
    $('#shamsi_date_dob').persianDatepicker({
        // minDate: new persianDate().subtract('day', 1).valueOf(),
        maxDate: new persianDate(),
        format: 'YYYY-MM-DD',
        autoClose: true,
        initialValue: true,
        initialValueType: 'persian',
        calendar: {
            persian: {
                locale: 'en'
            }
        }
    });
    $('#joining_date_shamsi').persianDatepicker({
        // minDate: new persianDate().subtract('day', 1).valueOf(),
        maxDate: new persianDate(),
        format: 'YYYY-MM-DD',
        autoClose: true,
        initialValue: true,
        initialValueType: 'persian',
        calendar: {
            persian: {
                locale: 'en'
            }
        }
    });
</script>

@endsection


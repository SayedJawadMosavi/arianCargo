@extends('back.layouts.app')

@section('title', 'Language Settings')
@section('content')

<div class="card">
    <div class="card-header">
        <h4 class="card-title">
            Update Setting
        </h4>

    </div>
    <div class="card-body">
        <form class="" action="{{ route('setting.post', $setting->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('POST')

          
            <div class="form-row">

                <div class="form-group col-md-3 mb-0 mt-3">
                    <label class="custom-switch form-switch mb-0">

                        <input type="checkbox" name="en" class="custom-switch-input" @if(isset($setting)) @if($setting->en == 1) {{'checked'}} @endif @endif >
                        <span class="custom-switch-indicator custom-switch-indicator-md"></span>
                        <span class="custom-switch-description">English</span>
                    </label>

                </div>
                <div class="form-group col-md-3 mb-0 mt-3">
                    <label class="custom-switch form-switch mb-0">

                        <input type="checkbox" name="fa" class="custom-switch-input" @if(isset($setting)) @if($setting->fa == 1) {{'checked'}} @endif @endif >
                        <span class="custom-switch-indicator custom-switch-indicator-md"></span>
                        <span class="custom-switch-description">Dari</span>
                    </label>


                </div>
                <div class="form-group col-md-3 mb-0 mt-3">
                    <label class="custom-switch form-switch mb-0">

                        <input type="checkbox" name="pa" class="custom-switch-input" @if(isset($setting)) @if($setting->pa == 1) {{'checked'}} @endif @endif >
                        <span class="custom-switch-indicator custom-switch-indicator-md"></span>
                        <span class="custom-switch-description">Pashto</span>
                    </label>


                </div>
            </div>

            <div class="form-footer mt-2">
                <input type="submit" class="btn btn-primary" value="Publish">
            </div>
        </form>

    </div>
</div>
@endsection
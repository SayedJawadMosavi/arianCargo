@extends('layouts.app')

@section('title', 'New Document')
@section('content')

<div class="card mt-4">
    <div class="card-header d-flex justify-content-between">
        @if (session()->has('success') || session()->has('error') )
            @include('layouts.partials.components.alert')
        @endif
        <h4 class="card-title">
            @if(isset($document))
            {{ __('home.edit_document') }}
            @else
            {{ __('home.new_document') }}
            @endif
        </h4>
        <a href="{{route('document.index')}}" class="btn btn-primary">{{ __('home.all_documents') }}</a>

    </div>
    <div class="card-body ">
        <form action="{{isset($document) ? route('document.update', $document) : route('document.store')}}" method="POST" enctype="multipart/form-data">
            @csrf
            @if(isset($document))
            @method('PUT')
            @else
            @method('POST')
            @endif

            <div class="form-row mb-3">


                <div class="col-xl-4 mb-3">
                    <label for="validationServer04">{{ __('home.category') }}
                        <span class="badge bg-primary">
                            <a class="" data-bs-effect="effect-sign" data-bs-toggle="modal" href="#modaldemo8"><span class="badge bg-primary"> <i class="fe fe-plus "></i></span></a>
                    </label>
                    <select class="form-select form-control select2 @error('category_id') {{'is-invalid'}} @enderror" id="validationServer04" aria-describedby="validationServer04Feedback" required name="category_id">
                        <option selected disabled value="">Choose...</option>

                        @foreach($categories as $category)
                        <option value="{{$category->id}}" @if(isset($document)) @if($document->document_category_id == $category->id) selected = 'selected' @endif @endif >{{$category->name}}</option>
                        @endforeach

                    </select>
                    @error('category_id')
                    <div id="" class="invalid-feedback">{{$message}}</div>
                    @enderror
                </div>
                @if(!isset($document))
                <div class="col-xl-4 mb-3">
                    <label class="" for="inlineFormInputGroup"> {{__('home.documents')}}</label>
                    <input type="file" class="form-control " name="file[]" multiple>
                    @error('photo')
                    <span class="alert text-danger">{{$message}}</span>
                    @enderror
                </div>@endif
                @if ($settings->date_type=='shamsi')
                <div class="col-xl-4 mb-3">
                    <label for="validationServer01">{{ __('home.date') }}</label>
                    <input type="text" class="form-control form-control @error('date') {{'is-invalid'}} @enderror" name="shamsi_date" autocomplete="off" id="dates" value="{{isset($document) ? $document->date : old('date')}}">

                    @error('date')
                    <div id="" class="invalid-feedback">{{$message}}</div>
                    @enderror
                </div>
                @else
                <div class="col-xl-4 mb-3">
                    <label for="validationServer01">{{ __('home.date') }}</label>
                    <input type="date" class="form-control @error('date') {{'is-invalid'}} @enderror" id="date" name="miladi_date" value="{{ isset($document) ? $document->miladi_date : date('Y-m-d') }}">
                    @error('date')
                    <div id="" class="invalid-feedback">{{$message}}</div>
                    @enderror
                </div>
                @endif
                <div class="col-xl-12 mb-3">
                    <label for="validationServer01">{{ __('home.description') }}</label>
                    <input type="text" class="form-control @error('name') {{'is-invalid'}} @enderror" id="name" name="name" value="{{isset($document) ? $document->description : old('name')}}" autocomplete="off" >
                    @error('name')
                    <div id="" class="invalid-feedback">{{$message}}</div>
                    @enderror
                </div>

            </div>

            <div class="form-footer mt-2">
                <input type="submit" class="btn btn-primary" value="@if(isset($document))
                    {{ __('home.update') }}
                @else
                    {{ __('home.save') }}
                @endif">
            </div>

        </form>

    </div>
</div>
<div class="modal fade" id="modaldemo8">
    <div class="modal-dialog modal-dialog-centered text-center modal-sm" role="document">
        <div class="modal-content modal-content-demo">
            <div class="modal-header">
                <h6 class="modal-title">{{ __('home.new_category') }}</h6><button aria-label="Close" class="btn-close" data-bs-dismiss="modal"><span aria-hidden="true">&times;</span></button>
            </div>
            <form action="{{route('document_category.store')}}" method="POST" enctype="multipart/form-data">
                <div class="modal-body">

                    @csrf

                    @method('POST')


                    <div class="form-row mb-3">

                        <div class="col-xl-12 mb-3">
                            <label for="validationServer01">{{ __('home.name') }}</label>
                            <input type="text" class="form-control @error('name') {{'is-invalid'}} @enderror" id="name" name="name" value="">
                            <input type="hidden" class="form-control" name="types" value="document_type">
                            @error('name')
                            <div id="" class="invalid-feedback">{{$message}}</div>
                            @enderror
                        </div>

                    </div>

                    <div class="form-footer mt-2">
                        <input type="submit" class="btn btn-primary" value="{{ __('home.send') }}">
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>
@endsection
@section('pagescript')
<script>
    $('#dates').persianDatepicker({
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
<script>
    showData('income');

    function showData(value) {


        $.ajax({
            url: "{{URL::asset('/')}}" + "document-category/" + value,
            method: 'GET',
            success: function(data) {
                $('.form-select').html(data.data);

            }
        });

    }
</script>
@endsection

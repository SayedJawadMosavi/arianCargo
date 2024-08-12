@extends('layouts.app')
@section('title', 'All Documents')

@section('content')

<div class="card mt-4">
    @if (session()->has('success') || session()->has('error') )
    @include('layouts.partials.components.alert')
    @endif
    <div class="card-header d-flex justify-content-between">
        <h3 class="card-title">{{ __('home.documents') }}</h3>
        @can('document.create')
        <a href="{{ route('document.create') }}" class="btn btn-primary mx-5">{{ __('home.new_document') }}</a>
        @endcan
    </div>

    <div class="card-body pt-4">
        <div class="col-sm-12 my-3">
            @php $data = 'hey'; @endphp
            <form class="" action="{{ route('document.filter') }}" method="POST">
                <x-date-component :data="$data" />
            </form>

        </div>
        <div class="grid-margin">
            <div class="">
                <div class="panel panel-primary">
                    <div class="tab-menu-heading tab-menu-heading-boxed">
                        <div class="tabs-menu-boxed">
                            <!-- Tabs -->
                            <ul class="nav panel-tabs document-sale">
                                <li><a href="#tab1" class="active" data-bs-toggle="tab">{{ __('home.all_documents') }}</a></li>
                                <li><a href="#tab2" data-bs-toggle="tab" class="text-dark">{{ __('home.trashed') }}</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="panel-body tabs-menu-body border-0 pt-0">
                        <div class="tab-content">
                            <div class="tab-pane active" id="tab1">
                                <div class="table-responsive">
                                    <table id="file-datatable" class="table table-bordered table-striped text-nowrap mb-0 table-hover">
                                        <thead class="border-top">
                                            <tr>
                                                <th>{{ __('home.sn') }}</th>
                                                <th>{{ __('home.description') }}</th>

                                                <th>{{ __('home.category') }}</th>

                                                <th>{{ __('home.date') }}</th>

                                                <th>{{ __('home.action') }}</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            @foreach($documents as $document)
                                            <tr>

                                                <td>{{$document->id}}</td>
                                                <td>{{$document->description}}</td>

                                                <td>{{$document->category->name}}</td>


                                                <td>
                                                    @if ($settings->date_type=='shamsi')
                                                    {{$document->shamsi_date}}
                                                    @else
                                                    {{$document->miladi_date}}
                                                    @endif
                                                </td>

                                                <td class="d-flex">
                                                    <div class="">
                                                        @can('document.edit')
                                                        <a title="{{__('home.documents')}}" class="btn f-third btn-sm" data-bs-toggle="modal" data-bs-target="#show_document_modal" onclick="show_document('{{$document->id}}')"> <i class="fa fa-image"></i></a>
                                                        @endcan
                                                        @can('document.edit')
                                                        <a class="btn text-primary btn-sm" href="{{route('document.edit', $document)}}" data-bs-toggle="tooltip" data-bs-original-title="{{ __('home.edit') }}"><span class="fe fe-edit fs-16"></span></a>
                                                        @endcan
                                                        @can('document.delete')
                                                        <form action="{{route('document.destroy', $document)}}" method="POST" class="d-inline">
                                                            @method('delete')
                                                            @csrf
                                                            <button type="submit" data-bs-toggle="tooltip" data-bs-original-title="{{ __('home.delete') }}" class="btn text-danger btn-sm"><span class="fe fe-trash-2 fs-16"></span></button>
                                                        </form>
                                                        @endcan
                                                    </div>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane" id="tab2">
                                <div class="table-responsive">
                                    <table id="file-datatable" class="table table-bordered table-striped text-nowrap mb-0 table-hover">
                                        <thead class="border-top">
                                            <tr>
                                                <th>{{ __('home.sn') }}</th>
                                                <th>{{ __('home.description') }}</th>

                                                <th>{{ __('home.category') }}</th>

                                                <th>{{ __('home.date') }}</th>

                                                <th>{{ __('home.action') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($trashed as $document)
                                            <tr class="border-bottom">
                                                <td>{{$document->id}}</td>
                                                <td>{{$document->description}}</td>

                                                <td>{{$document->category->name}}</td>


                                                <td>
                                                    @if ($settings->date_type=='shamsi')
                                                    {{$document->shamsi_date}}
                                                    @else
                                                    {{$document->miladi_date}}
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="g-2 ">

                                                        <form action="{{route('document.restore', $document->id)}}" method="POST" class="d-inline">
                                                            @method('POST')
                                                            @csrf
                                                            <button type="submit" data-bs-toggle="tooltip" data-bs-original-title="Restore" class="btn text-primary btn-sm"><span class="fe fe-repeat fs-14"></span></button>
                                                        </form>
                                                        <form action="{{route('document.forceDelete', $document->id)}}" method="POST" class="d-inline">
                                                            @method('delete')
                                                            @csrf
                                                            <button type="submit" data-bs-toggle="tooltip" data-bs-original-title="Delete Permanently" class="btn text-danger btn-sm"><span class="fe fe-trash-2 fs-14"></span></button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>


                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="show_document_modal">
        <div class="modal-dialog modal-dialog-centered text-center modal-lg" role="document">
            <div class="modal-content modal-content-demo">
                <div class="modal-header">
                    <h4 class="modal-title" id="exampleModalLabel">{{__('home.documents')}}</h4>
                    <a href="#" onclick="openFile()" class="ml-2 btn btn-primary btn-sm pull-left btn-rounded pl-4 pr-4">{{__('home.new_document')}}</a>
                </div>
                <div class="modal-body">
                    <form id="editForm" enctype="multipart/form-data">
                        {{csrf_field()}}


                        <div class="form-row mb-3">

                            <div class="col-xl-12 mb-3">

                                <input type="file" style="display: none" name="file[]" multiple="multiple" id="editFile">
                                <input type="hidden" name="name" id="name">
                                <input type="hidden" name="id" id="id">
                            </div>

                        </div>


                    </form>
                    <div class="row" id="imagess">

                    </div>
                </div>
                <div class="modal-footer">
                    <div class="col-md-12 modal-confirm btn-group-sm">
                        <button type="button" class="pl-4 pr-4 btn btn-primary btn-rounded btn-sm" onclick="insertSelectedFile()">{{__('home.save')}}</button>
                        <button type="button" class="pl-4 pr-4 btn btn-info btn-rounded btn-sm" onclick="updateSelectedFile()">{{__('home.edit')}}</button>
                        <button type="button" class="pl-4 pr-4 btn btn-danger btn-rounded btn-sm" data-bs-dismiss="modal">{{__('home.cancel')}}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- card-body --}}
</div>
@endsection
@section('pagescript')
<script>
    var baseUrl = "{{ asset('') }}";

    function show_document(d_id) {
        $('#id').val(d_id);


        var url = "{{url('document')}}/" + d_id;
        $.ajax({
            url: url,
            method: "get",
            success: function(data) {
                console.log("data:  " + data);
                if (!data) {
                    x =
                        '<div class="col-12">' +
                        '<center>' +
                        '<h5 class="">gallary is empty</h5>' +
                        '</center>' +
                        '</div>';
                } else {
                    var p = data.split(",");
                    var x = "";

                    for (var i = 0; i < p.length; i++) {
                        // Remove leading and trailing spaces from the image path
                        var imagePath = p[i].trim();

                        // Check if imagePath is empty or undefined
                        if (imagePath === "") {
                            continue; // Skip empty paths
                        }

                        var extension = imagePath.split('.').pop().toUpperCase();
                        var select = imagePath.split("/");

                        if (extension === "PNG" || extension === "JPG" || extension === "JPEG") {
                            var imageUrl = baseUrl + imagePath; // Construct the image URL

                            // Construct the image tag
                            x += '<div class="col-lg-3 pb-3 col-md-4 col-sm-6">' +
                                '<div class="card" style="width:10rem;">' +
                                '<a class="pb-0 pt-1" href="#">' +
                                '<center><span style="font-size: 1.7rem; margin-right: 23px;">' +
                                '<img class="img img-fluid" style="width:100%; height:140px;" src="' + imageUrl + '"/>' +
                                '</span></center>' +
                                '</a>' +
                                '<div class="row pt-1 pb-1 pr-2">' +
                                '<div class="col-4"> <a style="cursor:pointer" href="' + imageUrl + '" class="fa fa-download fa-sm text-primary"> </a></div>' +
                                '<div class="col-4"> <a style="cursor:pointer" href="#" onclick="editSelectedFile(\'' + select[select.length - 1] + '\',' + d_id + ')"  class="fa fa-edit fa-sm text-info"></a></div>' +
                                '<div class="col-4"> <a style="cursor:pointer" href="#" onclick="deleteSelectedFile(\'' + select[select.length - 1] + '\',' + d_id + ')"  class="fa fa-trash fa-sm text-danger"> </a></div>' +
                                '</div>' +
                                '</div>' +
                                '</div>';
                        } else if (extension === "PDF") {
                            // Construct link tag for PDF files
                            x += '<div class="col-lg-3 pb-3 col-md-4 col-sm-6">' +
                                '<div class="card" style="width:10rem;">' +
                                '<a class="pb-0 pt-1" href="' + baseUrl + p[i].trim() + '">' +
                                ' <center> <span style="font-size: 1.7rem;margin-right:35px"> <span class="text-danger fa fa-file-pdf-o fa-5x" style="width:100%"> </span> </span></center>' +
                                '</a>' +
                                '<div class="row pt-1 pb-1 pr-2">' +
                                '<div class="col-4"> <a style="cursor:pointer" href="' + baseUrl + p[i].trim()+ '" class="fa fa-download fa-sm text-primary"> </a></div>' +
                                '<div class="col-4"> <a style="cursor:pointer" onclick="editSelectedFile(\'' + select[select.length - 1] + '\',' + d_id + ')"  class="fa fa-edit fa-sm text-info"></a></div>' +
                                '<div class="col-4"> <a style="cursor:pointer" onclick="deleteSelectedFile(\'' + select[select.length - 1] + '\',' + d_id + ')"  class="fa fa-trash fa-sm text-danger"> </a></div>' +
                                '</div>' +
                                '</div>' +
                                '</div>';
                        } else if (extension === "DOCX" || extension === "DOC") {
                            // Construct link tag for DOCX/DOC files
                            x += '<div class="col-lg-3 pb-3 col-md-4 col-sm-6">' +
                                '<div class="card" style="width:10rem;">' +
                                '<a class="pb-0 pt-1" href="' + baseUrl + p[i].trim() + '">' +
                                ' <center> <span style="font-size: 1.7rem;margin-right:35px"> <span class="text-info fa fa-file-word-o fa-5x"style="font-size:95px;width:100%;height:100px"> </span> </span></center>' +
                                '</a>' +
                                '<div class="row pt-1 pb-1 pr-2">' +
                                '<div class="col-4"> <a style="cursor:pointer" href="' + baseUrl + p[i].trim()+ '" class="fa fa-download fa-sm text-primary"> </a></div>' +
                                '<div class="col-4"> <a style="cursor:pointer" onclick="editSelectedFile(\'' + select[select.length - 1] + '\',' + d_id + ')"  class="fa fa-edit fa-sm text-info"></a></div>' +
                                '<div class="col-4"> <a style="cursor:pointer" onclick="deleteSelectedFile(\'' + select[select.length - 1] + '\',' + d_id + ')"  class="fa fa-trash fa-sm text-danger"> </a></div>' +
                                '</div>' +
                                '</div>' +
                                '</div>';
                        }
                    }

                }

                $("#imagess").html(x);
            },
            error: function(err) {

            }
        })
    }
    /* Begin :: Insert Selected Image to the List */
    function openFile() {
        $('#editFile').click();
        console.log("Insert Button Clicked");
    }

    function insertSelectedFile() {
        var form = $('#editForm')[0];
        var formData = new FormData(form);
        var path = "{{url('insert_file')}}";
        $.ajax({
            data: formData,
            method: "post",
            url: path,
            processData: false,
            contentType: false,

            success: function(data) {
                if ($.isEmptyObject(data.error)) {
                    $('#editForm')[0].reset();
                    toastr.success(data.success);
                    $('#show_document_modal').modal('hide');
                } else if (!$.isEmptyObject(data.error)) {
                    $.each(data.error, function(key, value) {

                        toastr.error(value);
                    });
                }
            },
            error: function(err) {}
        });
    }
    /* End :: Insert Selected Image to the List */
    /* Begin :: Edit Selected Images of the List */
    function editSelectedFile(name, id) {
        $('#editFile').click();
        $('#name').val(name);
        $('#id').val(id);
    }

    function updateSelectedFile() {

        var form = $('#editForm')[0];
        var formData = new FormData(form);
        var path = "{{url('editFile')}}";
        $.ajax({
            data: formData,
            method: "post",
            url: path,
            processData: false,
            contentType: false,

            success: function(data) {
                if ($.isEmptyObject(data.error)) {
                    $('#editForm')[0].reset();
                    toastr.success(data.success);
                    $('#show_document_modal').modal('hide');
                } else if (!$.isEmptyObject(data.error)) {
                    $.each(data.error, function(key, value) {
                        console.log(value);
                        toastr.error(value);
                    });
                }
            },
            error: function(err) {}
        });
    }
    /* End :: Edit Selected Images of the List */
    /* Begin :: Delete Record */
    function deleteSelectedFile(name, id) {
        console.log(name + "   " + id);


        swal.fire({
            title: "آیا مطمین هستید؟",
            text: "این پروسه قابل بازگشت نمی باشد",
            showCancelButton: true,
            type: "error",

            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'حذف ',
            cancelButtonText: 'انصراف!',
            confirmButtonClass: 'ladda-button btn btn-danger btn-sm pl-3 pr-3 mr-1 btn-rounded',
            cancelButtonClass: 'ladda-button btn btn-info btn-sm mr-1 btn-rounded',
            buttonsStyling: false
        }).then(function(isConfirm) {
            if (isConfirm) {
                var _token = "{{ csrf_token() }}";
                var data = {
                    _token: _token
                }
                console.log(id);
                $.ajax({

                    data: data,
                    method: "DELETE",
                    url: "{{ url('deleteFile') }}" + '/' + name + '/' + id,
                    success: function(data) {
                        swal.fire({
                            text: "فایل از لیست موفقانه حذف گردید !",
                            title: "حذف گردید !",
                            type: "success",
                            confirmButtonText: 'بستن ',
                            confirmButtonClass: 'ladda-button btn btn-info pl-4 pr-4 btn-sm mr-1 btn-rounded',
                            buttonsStyling: false
                        });
                        var x = true;
                        if (x) {
                            $("#show_document_modal").modal("hide");
                            x = false;
                        }
                        if (!x) {
                            $("#show_document_modal").modal("show");
                        }

                        // $("#update_document_modal").modal("show");

                    },
                    error: function(err) {
                        swal({
                            title: " حذف نگردید!",
                            text: "سرور مشکل دارد",
                            type: "error",
                            confirmButtonText: 'بستن ',
                            confirmButtonClass: 'ladda-button btn btn-info btn-sm pl-4 pr-4 mr-1 btn-rounded',
                            buttonsStyling: false
                        });
                    }
                })
            }
        });
        $("#show_document_modal").modal("show");

    }
</script>
<style>
    .swal2-container {
        z-index: 20000 !important;
    }
</style>
@endsection

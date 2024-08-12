@extends('layouts.app')
@section('title', 'All Staff')

@section('content')

<div class="card mt-4">
    @if (session()->has('success') || session()->has('error') )
    @include('layouts.partials.components.alert')
    @endif
    <div class="card-header">
        <h3 class="card-title">{{ __('home.staffs') }}</h3>
        <a href="{{ route('staff.create') }}" class="btn btn-outline-primary mx-5">{{ __('home.new_staff') }}</a>

    </div>

    <div class="card-body pt-4">
        <div class="grid-margin">
            <div class="">
                <div class="panel panel-primary">
                    <div class="tab-menu-heading tab-menu-heading-boxed">
                        <div class="tabs-menu-boxed">
                            <!-- Tabs -->
                            <ul class="nav panel-tabs product-sale">
                                <li><a href="#tab1" class="active" data-bs-toggle="tab">{{ __('home.all_staff') }}</a></li>

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
                                                <th scope="col"> {{__('home.name')}} </th>
                                                <th scope="col">{{__('home.fatherName')}}</th>

                                                <th scope="col">{{__('home.salary')}}</th>
                                                <th scope="col">{{__('home.tazkira_no')}}</th>
                                                <th scope="col">{{__('home.education')}}</th>
                                                <th scope="col">{{__('home.phone')}}</th>
                                                <th scope="col">{{__('home.position')}}</th>
                                                <!-- <th scope="col">{{__('home.shareholders')}}</th> -->
                                                <th scope="col">{{__('home.loan')}}</th>
                                                <th scope="col">{{__('home.action')}}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php $c = 1 @endphp
                                            @foreach ($staffs as $staff)

                                            <tr>
                                                <td scope="row">{{$c++}} </td>

                                                <td>{{$staff->name}} </td>
                                                <td>{{$staff->fathername}} </td>

                                                <td>{{$staff->salary}}</td>
                                                <td>{{$staff->tazkira_number}}</td>
                                                <td>{{$staff->education}}</td>
                                                <td>{{$staff->mobile}}</td>
                                                <td>{{$staff->position}}</td>


                                                <td><span style="color:{{$staff->loan >=0 ?'green' : 'red'}}">{{$staff->loan}}</span></td>


                                                <td>
                                                    <div class="g-2 ">
                                                    <a title="View Document " type="button" class="btn f-third btn-sm" data-bs-toggle="modal" data-bs-target="#show_document_modal" onclick="show_document('{{$staff->id}}')"> <i class="fa fa-image"></i></a>

                                                        <a class="btn text-primary btn-sm" href="{{route('staff.edit', $staff)}}" data-bs-toggle="tooltip" data-bs-original-title="Edit"><span class="fe fe-edit fs-14"></span></a>
                                                        <a class="btn text-success btn-sm" href="{{route('staff.statement', $staff)}}" data-bs-toggle="tooltip" data-bs-original-title="Statement"><span class="fe fe-menu fs-14"></span></a>
                                                        <form action="{{route('staff.destroy', $staff)}}" method="POST" class="d-inline">
                                                            @method('delete')
                                                            @csrf
                                                            <button type="submit" data-bs-toggle="tooltip"  data-bs-original-title="Delete" class="btn text-danger btn-sm"><span class="fe fe-trash-2 fs-14"></span></button>
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
    {{-- card-body --}}
</div>
<div class="modal fade" id="show_document_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="exampleModalLabel">Document Gallery </h4>
                <a href="#" onclick="openFile()" class="ml-2 btn btn-primary btn-sm pull-left btn-rounded pl-4 pr-4" >Add</a>
            </div>
            <div class="modal-body">
                <form  id="editForm"   enctype="multipart/form-data">
                        {{csrf_field()}}
                        <input type="file" style="display: none" name="file[]" multiple="multiple" id="editFile">
                        <input type="hidden" name="name" id="name">
                        <input type="hidden" name="id" id="id">
                </form>
                    <div class="row" id="imagess">

                    </div>

            </div><!-- end of modal header -->
                <div class="modal-footer">
                    <div class="col-md-12 modal-confirm btn-group-sm">
                        <button type="button" class="pl-4 pr-4 btn btn-primary btn-rounded btn-sm" onclick="insertSelectedFile()">Save</button>
                        <button type="button" class="pl-4 pr-4 btn btn-info btn-rounded btn-sm" onclick="updateSelectedFile()">Edit</button>
                        <button type="button" class="pl-4 pr-4 btn btn-danger btn-rounded btn-sm" data-bs-dismiss="modal">close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('pagescript')
<script type="text/javascript">
  function show_document(d_id){
    $('#id').val(d_id);

    var url= "{{url('get_documents')}}/"+d_id;
    $.ajax({
        url  :url,
        method: "get",
        success: function(data){
            console.log("data:  " + data);
            if(!data){
                x =
                    '<div class="col-12">'+
                        '<center>'+
                            '<h5 class="">No File exist</h5>'+
                        '</center>'+
                    '</div>';
            }else{
                var p=data.split(",");
                        console.log(p);

                var extensions =[];
                var c=0;
                for (var i = 0; i < p.length; i++) {
                    var z =p[i].split(".");
                    extensions[c] = z[1];
                    c++;
                }
                var x = "";

                var counter = 0;
                for(var i=0; i<=extensions.length-1; i++){
                    var select = p[counter].split("/");
                    console.log("my image",select[3])
                    if(extensions[i] == "PNG" || extensions[i] == "JPG" || extensions[i] == "JPEG" || extensions[i] == "png" || extensions[i] == "jpg" || extensions[i] == "jpeg"){
                        x=x+
                        '<div class="col-lg-3 pb-3 col-md-4 col-sm-6">'+
                            '<div class="card" style="width:10rem;">'+
                                '<a class="pb-0 pt-1" href="#">'+
                                    ' <center> <span style="font-size: 1.7rem;margin-right: 23px;"> <img class="img img-fluid" style="width:100px;height:100px"  src="'+p[counter]+'"/> </span></center>'+
                                '</a>'+
                                '<div class="row pt-1 pb-1 pr-2">'+
                                    '<div class="col-4"> <a style="cursor:pointer" href="'+p[counter]+'" class="fa fa-download fa-sm text-primary"> </a></div>'+
                                    '<div class="col-4"> <a style="cursor:pointer" href="#" onclick="editSelectedFile(\''+ select[3] + '\','+ d_id+')"  class="fa fa-edit fa-sm text-info"></a></div>'+
                                    '<div class="col-4"> <a style="cursor:pointer" href="#" onclick="deleteSelectedFile(\''+ select[3] + '\','+ d_id+')"  class="fa fa-trash fa-sm text-danger"> </a></div>'+
                                '</div>'+
                            '</div>'+
                        '</div>';
                    }else if(extensions[i] == "pdf" || extensions[i] == "PDF"){
                        x=x+
                        '<div class="col-lg-3 pb-3 col-md-4 col-sm-6">'+
                            '<div class="card" style="width:10rem;">'+
                                '<a class="pb-0 pt-1" href="#">'+
                                    ' <center> <span style="font-size: 1.7rem;margin-right:35px"> <span class="text-danger fa fa-file-pdf-o fa-5x" style="font-size:95px"> </span> </span></center>'+
                                '</a>'+
                                '<div class="row pt-1 pb-1 pr-2">'+
                                    '<div class="col-4"> <a style="cursor:pointer" href="'+p[counter]+'" class="fa fa-download fa-sm text-primary"> </a></div>'+
                                    '<div class="col-4"> <a style="cursor:pointer" onclick="editSelectedFile(\''+ select[3] + '\','+ d_id+')"  class="fa fa-edit fa-sm text-info"></a></div>'+
                                    '<div class="col-4"> <a style="cursor:pointer" onclick="deleteSelectedFile(\''+ select[3] + '\','+ d_id+')"  class="fa fa-trash fa-sm text-danger"> </a></div>'+
                                '</div>'+
                            '</div>'+
                        '</div>';

                    }else if(extensions[i] == "docx" || extensions[i] == "doc" || extensions[i] == "DOCX" ||extensions[i] == "DOC"){
                        x=x+
                        '<div class="col-lg-3 pb-3 col-md-4 col-sm-6">'+
                            '<div class="card" style="width:10rem;">'+
                                '<a class="pb-0 pt-1" href="#">'+
                                    ' <center> <span style="font-size: 1.7rem;margin-right:35px"> <span class="text-info fa fa-file-word fa-5x"style="font-size:95px"> </span> </span></center>'+
                                '</a>'+
                                '<div class="row pt-1 pb-1 pr-2">'+
                                    '<div class="col-4"> <a style="cursor:pointer" href="'+p[counter]+'" class="fa fa-download fa-sm text-primary"> </a></div>'+
                                    '<div class="col-4"> <a style="cursor:pointer" onclick="editSelectedFile(\''+ select[3] + '\','+ d_id+')"  class="fa fa-edit fa-sm text-info"></a></div>'+
                                    '<div class="col-4"> <a style="cursor:pointer" onclick="deleteSelectedFile(\''+ select[3] + '\','+ d_id+')"  class="fa fa-trash fa-sm text-danger"> </a></div>'+
                                '</div>'+
                            '</div>'+
                        '</div>';
                    }else{
                        '<div class="col-lg-3 pb-3 col-md-4 col-sm-6">'+
                            '<div class="card" style="width:10rem;">'+
                                '<a class="pb-0 pt-1" href="#">'+
                                    ' <center> <span style="font-size: 1.7rem;margin-right:35px"> <span class="text-info fa fa-file fa-5x"style="font-size:95px"> </span> </span></center>'+
                                '</a>'+
                                '<div class="row pt-1 pb-1 pr-2">'+
                                    '<div class="col-4"> <a style="cursor:pointer" href="'+p[counter]+'" class="fa fa-download fa-sm text-primary"> </a></div>'+
                                    '<div class="col-4"> <a style="cursor:pointer" onclick="editSelectedFile(\''+ select[3] + '\','+ d_id+')"  class="fa fa-edit fa-sm text-info"></a></div>'+
                                    '<div class="col-4"> <a style="cursor:pointer" onclick="deleteSelectedFile(\''+ select[3] + '\','+ d_id+')"  class="fa fa-trash fa-sm text-danger"> </a></div>'+
                                '</div>'+
                            '</div>'+
                        '</div>';
                    }
                    counter++;
                }
            }

            $("#imagess").html(x);
        },
        error : function(err){

        }
    })
}
    /* Begin :: Insert Selected Image to the List */
    function openFile(){
        $('#editFile').click();
        console.log("Insert Button Clicked");
    }
    function insertSelectedFile(){
        var form = $('#editForm')[0];
        var formData = new FormData(form);
        var path = 'insertSelectedFile';
        $.ajax({
            data          :           formData,
            method        :           "post",
            url           :           path,
            processData       :       false,
            contentType       :       false,

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
    function editSelectedFile(name, id){
            $('#editFile').click();
            $('#name').val(name);
            $('#id').val(id);
        }
    function updateSelectedFile(){

        var form = $('#editForm')[0];
        var formData = new FormData(form);
        var path = 'editSelectedFile';
        $.ajax({
            data          :           formData,
            method        :           "post",
            url           :           path,
            processData       :       false,
            contentType       :       false,

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
    Swal.fire({
        title: "Are you sure ?",
        text: "you can not reverse this again",
        showCancelButton: true,

        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'delete ',
        cancelButtonText: 'cancel!',
        confirmButtonClass: 'ladda-button btn btn-danger btn-sm pl-3 pr-3 mr-1 btn-rounded',
        cancelButtonClass: 'ladda-button btn btn-info btn-sm mr-1 btn-rounded',
        buttonsStyling: false,
        customClass: {
        popup: 'your-swal-custom-class',
    },
    }).then(function (isConfirm) {
        if (isConfirm.value) {
            var _token = "{{ csrf_token() }}";
            var data = { _token: _token };
            console.log(id);
            $.ajax({
                data: data,
                method: "DELETE",
                url: "{{ url('deleteSelectedFile') }}" + '/' + name + '/' + id,
                success: function (data) {
                    Swal.fire({
                        text: "file has been deleted successfully !",
                        title: " deleted !",
                        icon: "success",
                        confirmButtonText: 'close ',
                        confirmButtonClass: 'ladda-button btn btn-info pl-4 pr-4 btn-sm mr-1 btn-rounded',
                        buttonsStyling: false
                    });

                    // If you want to hide the modal after deleting the file
                    $("#show_document_modal").modal("hide");
                },
                error: function (err) {
                    Swal.fire({
                        title: "not deleted!",
                        text: "server error",
                        icon: "error",
                        confirmButtonText: 'close ',
                        confirmButtonClass: 'ladda-button btn btn-info btn-sm pl-4 pr-4 mr-1 btn-rounded',
                        buttonsStyling: false
                    });
                }
            });
        }
    });
}

    /* End :: Delete Record */

</script>
<style>
    .your-swal-custom-class {
    z-index: 1051; /* Choose a value higher than your existing modal's z-index */
}
</style>
@endsection

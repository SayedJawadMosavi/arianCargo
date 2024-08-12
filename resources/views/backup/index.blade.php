@extends('layouts.app')
@section('title', 'All accounts')

@section('content')

<div class="card mt-4">
    {{-- @dd(session()->has('success')) --}}
    @if (session()->has('success') || session()->has('error') )
        @include('layouts.partials.components.alert')
    @endif

    <div class="card-header ">
        <h3 class="card-title">{{ __('home.backups') }}</h3>
        <a  class="btn btn-primary mx-5" onClick="getBacupDatabase()">{{ __('home.db_backup') }}</a>
        <a id="" onClick="getBacupFileDatabase()" class="btn btn-secondary mx-5"> {{ __('home.all_backup') }}</a>

    </div>

    <div class="card-body pt-4">

        <div class="table-responsive">
            <table id="data-table1" class="table table-bordered table-striped text-nowrap mb-0 table-hover">
                <thead>
                    <tr>
                        <th scope="col">{{ __('home.filename') }}</th>
                        <th scope="col">{{ __('home.date') }}</th>
                        <th scope="col">{{ __('home.size') }}</th>

                        <th scope="col">{{ __('home.action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($databases as $database)
                    <tr>
                        <td>{{ $database->getBasename() }}</td>
                        <td>{{ Carbon\Carbon::createFromTimestamp($database->getATime())->toDateTimeString() }}</td>
                        <td>{{ number_format($database->getSize() / 1024 / 1024 ,2) }} MB</td>
                        <td>
                            <a href="{{ url('backups/'.$database->getBasename())}}"
                               class="text-blue hover:underline"><i class="fa fa-download" style="margin-left: 9px;"></i></a>
                            <a onClick="DeleteBackup('{{$database->getBasename()}}')" class="text-danger hover:underline "><i class="fa fa-trash" style="margin-left: 9px;" ></i></a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center">
                            No Backup yet.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
    {{-- card-body --}}
</div>
@endsection

<script>
    function getBacupFileDatabase(){
        var _token     = "{{ csrf_token() }}";
        var data       = { _token: _token }

        $.ajax({

            data            : data,
            method          : "post",
            url             : "{{ url('backups')}}",
            beforeSend      :  function(){
                Swal.fire({
                title: ' لطفا منتظر باشید ...' ,
                allowEscapeKey: false,
                allowOutsideClick: false,
                onOpen: () => {
                swal.showLoading();
                }
            })
            },
            success         : function(data){
                    swal.fire({
                    text                : "ازفایل ودیتابیس  بک اپ گرفته شد !",
                    title               : "بک اپ گردید !",
                    type                : "success",
                    confirmButtonText   : 'بستن ',
                    confirmButtonClass  : 'ladda-button btn btn-info pl-4 pr-4 btn-sm mr-1 btn-rounded',
                    buttonsStyling      : false
                });
                window.location.href="{{url('backups')}}";
            },
            error  :function(err){
                swal({
                    title               : " حذف نگردید!",
                    text                : "سرور مشکل دارد",
                    type                : "error",
                    confirmButtonText   : 'بستن ',
                    confirmButtonClass  : 'ladda-button btn btn-info btn-sm pl-4 pr-4 mr-1 btn-rounded',
                    buttonsStyling: false
                });
            }
        })

}
function getBacupDatabase(){
        var _token     = "{{ csrf_token() }}";
        var data       = { _token: _token }

        $.ajax({

            data            : data,
            method          : "get",
            url             : "{{ route('backups.create')}}",
            beforeSend      :  function(){
                Swal.fire({
                title: ' لطفا منتظر باشید ...' ,
                allowEscapeKey: false,
                allowOutsideClick: false,
                onOpen: () => {
                swal.showLoading();
                }
            })
            },
            success         : function(data){
                    swal.fire({
                    text                : "از دیتابیس  بک اپ گرفته شد !",
                    title               : "بک اپ گردید !",
                    type                : "success",
                    confirmButtonText   : 'بستن ',
                    confirmButtonClass  : 'ladda-button btn btn-info pl-4 pr-4 btn-sm mr-1 btn-rounded',
                    buttonsStyling      : false
                });
                window.location.href="{{url('backups')}}";
            },
            error  :function(err){
                swal({
                    title               : " حذف نگردید!",
                    text                : "سرور مشکل دارد",
                    type                : "error",
                    confirmButtonText   : 'بستن ',
                    confirmButtonClass  : 'ladda-button btn btn-info btn-sm pl-4 pr-4 mr-1 btn-rounded',
                    buttonsStyling: false
                });
            }
        })


}
function DeleteBackup(filename){
    swal.fire({
        title: 'آیا میخواهید حذف شود؟',
        text: "شما قادر به برگشت آن نخواهید شد!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'بلی !',
        cancelButtonText: 'نخیر!',
    }).then((result) => {
        var _token = "{{ csrf_token() }}";
        if (result.isConfirmed) {
            jQuery.ajax({
                url : "{{URL::asset('')}}"+'backups/'+filename,
                type: "delete",
                dataType : 'json',
                data : {filename:filename , _token : _token },
                success: function(data) {
                    swal.fire("انجام شد!", "موفقانه حذف گردید!", "success");
                    window.location.href="{{url('backups')}}";

                },
                error: function() {
                    swal.fire("مشکل وجود دارد!", "دوباره کوشش نمایید", "error");
                }
            });
        }
    });
}
</script>

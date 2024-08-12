@extends('layouts.app')

@section('title', 'New Rate')
@section('content')

<div class="card mt-4">
    <div class="card-header d-flex justify-content-between">
        <h4 class="card-title">

            {{ __('home.currency_rates') }}

        </h4>
        @if (session()->has('success') || session()->has('error') )
        @include('layouts.partials.components.alert')
        @endif
        @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif
    </div>
    <div class="card-body ">
        <form action="{{route('rate.store')}}" method="POST" enctype="multipart/form-data">
            @csrf

            @method('POST')



            <div class="form-row mb-3">


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
                                    <th scope="col"> {{__('home.from_currency')}}</th>
                                    <th scope="col"> {{__('home.base_currency')}}</th>
                                    <th> {{__('home.currency_rates')}}</th>
                                    <th> {{__('home.action')}}</th>
                                </tr>
                            </thead>
                            <tbody id="tbody">

                            </tbody>

                        </table>
                    </div>
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
        <table class="table table-sm table-striped table-bordered mt-3" id="datatable">
            <thead>
                <tr>

                    <th scope="col"> {{__('home.from_currency')}}</th>
                    <th scope="col"> {{__('home.base_currency')}}</th>
                    <th scope="col"> {{__('home.currency_rates')}}</th>
                    <th scope="col"> {{__('home.action')}}</th>
                </tr>
            </thead>
            <tbody>
                @php $res = null; @endphp

                @foreach ($data as $obj)
                        @foreach ($obj as $ress)
                            @if(isset($ress))
                            <tr>
                                {{-- <td>
                                    @if ($settings->date_type=='shamsi')
                                    {{$ress->shamsi_date}}
                                    @else
                                    {{$ress->miladi_date}}
                                    @endif
                                </td> --}}
                                <td>
                                    {{$ress->treasury->name}}
                                </td>
                                <td>
                                    {{$ress->toTreasury->name}}
                                </td>
                                <td class="update" data-pk="{{ $ress->id }}" data-name="rate" data-type="text" data-title="Enter Rate">
                                    {{$ress->rate}}
                                </td>
                                <td>
                                @if($ress->operation == "multiply")
                                <label class="btn btn-sm btn-primary">{{ __('home.multiply') }}</label>
                                @else
                                    <label class="btn  btn-sm btn-secondary">{{ __('home.divide') }}</label>
                                @endif
                                </td>

                            </tr>
                            @endif
                        @endforeach
                    @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
{{-- @dd($currencies) --}}
@section('pagescript')

<script>
    $.fn.editable.defaults.mode = 'inline';
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': '{{csrf_token()}}'
        }
    });
    $('.update').editable({
        url: "{{url('rateupdate')}}",
        type: 'text',
        pk: 1,
        name: 'rate',
        title: 'Enter Rate'
    });
    $(document).ready(function() {

        var count = 1;

        dynamic_field(count);

        function dynamic_field(number) {
            html = '<tr>';
            html += "<td>" +
                '<select class="form-control jstreasury" onchange="showCurrency(' + number + ');"  name="purchase[]" id="from_currency' + number + '">' +
                "<option readonly>{{__('home.please_select')}}</option>" +
                +"@foreach ($currencies as $obj)" +
                "<option value='{{$obj->id}}'>" + '{{$obj->name}}' + "</option>" +
                "@endforeach" +
                "</select> " +
                '@error("purchase")'
            '<span class="alert text-danger">' + '{{$message}}' + '</span>' +
            '@enderror' +
            "</td>"
            html += "<td>" +
                '<select class="form-control jstreasury"  name="sell[]" id="to_currency' + number + '">' +
                +"@foreach ($currencies as $obj)" +
                "<option value='{{$obj->id}}'>" + '{{$obj->name}}' + "</option>" +
                "@endforeach" +
                "</select> " +
                '@error("sell")'
            '<span class="alert text-danger">' + '{{$message}}' + '</span>' +
            '@enderror' +
            "</td>"
            html += '<td> <input type="number" step="0.0001" class="form-control " name="rate[]" id="rate" >' +
                '@error("rate")'
            '<span class="alert text-danger">' + '{{$message}}' + '</span>' +
            '@enderror' +
            '</td>'
            html += "<td>" +
                '<select class="form-control " name="operation[]" id="operation">' +
                '<option value="multiply">' + "{{__('home.multiply')}}" + '</option> ' +
                '<option value="divide" >' + "{{__('home.divide')}}" + '</option>' +
                '</select>' +
                '@error("sell")'
            '<span class="alert text-danger">' + '{{$message}}' + '</span>' +
            '@enderror' +
            "</td>"
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

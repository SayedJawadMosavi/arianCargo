@extends('layouts.app')
@section('title', 'Due Vendor Report')

@section('content')

<div class="card mt-4">
    {{-- @dd(session()->has('success')) --}}
    @if (session()->has('success') || session()->has('error') )
    @include('layouts.partials.components.alert')
    @endif

    <div class="card-header ">
        <h3 class="card-title">{{ __('home.due_vendor_report') }}</h3>
    </div>
    <div class="card-body pt-4">
        <div class="col-sm-12 my-3">
            <form action="{{ route('report.due_vendor.post') }}" method="POST">
                {{-- <x-date-component :data="$categories"/> --}}
                @csrf
                @method('POST')
                <div class="form-row align-items-center my-5 offset-md-1">

                    <div class="col-xl-3">
                        <label for="validationServer04">{{ __('home.vendors') }}</label>
                        <select class="form-selects form-control" id="validationServer04" aria-describedby="validationServer04Feedback" required name="vendor_id">
                            <option value="all">{{ __('home.all') }}</option>
                            @foreach($vendors as $obj)
                            <option value="{{$obj->id}}"> {{ $obj->company }} - {{ $obj->contact_person }}</option>
                            @endforeach
                        </select>

                    </div>


                    <div class="col-6 col-sm-2 ">
                        <label class="" for="inlineFormInputGroup"> </label>
                        <button type="submit" class="btn btn btn-outline-primary" style="margin-top: 29px"> {{__('home.send')}}</button>
                    </div>
                </div>

            </form>
            <table class="table table-bordered">

                @isset($sums)
                <tr>
                    @foreach ($sums as $k=>$v)
                    @if($v < 0)
                    <th>{{$k}}</th>
                    <th>{{$v}}</th>
                    @endif
                    @endforeach
                </tr>
                @endisset
            </table>
        </div>
        <div class="table-responsive">
            <table id="file-datatable" class="table table-bordered table-striped text-nowrap mb-0 table-hover">
                <thead>
                    <th>{{ __('home.sn') }}</th>

                    <th>{{ __('home.vendor') }}</th>
                    <th>{{ __('home.contact_person') }}</th>
                    <th>{{ __('home.mobile') }}</th>
                    <th>{{ __('home.currency') }}</th>
                    <th>{{ __('home.balance') }}</th>

                    </tr>
                </thead>

                @php $gquantity =0; @endphp
                <tbody>
                    @isset($logs)

                    @forelse($logs as $obj)

                    @if($obj->amount < 0) <tr>
                        <td>{{$loop->iteration}}</td>

                        <td>{{$obj->vendor->company}}</td>
                        <td>{{$obj->vendor->contact_person}}</td>
                        <td>{{$obj->vendor->mobile}}</td>
                        <td>{{$obj->currency->name}}</td>
                        <td>{{number_format($obj->amount)}} </td>

                        </tr>
                        @endif
                        @php
                        $gquantity += $obj->amount;

                        @endphp
                        @empty
                        @endforelse
                        @endisset
                </tbody>

                <tfoot>
                    <tr>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th>{{ __('home.total') }}</th>
                        <th>{{ number_format($gquantity) }}</th>

                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    {{-- card-body --}}
</div>
@endsection

@extends('layouts.app')
@section('title', 'All vendors')

@section('content')

<div class="card mt-4">
    @if (session()->has('success') || session()->has('error') )
        @include('layouts.partials.components.alert')
    @endif
    <div class="card-header">
        <h3 class="card-title">{{ __('home.vendors') }}</h3>
        <a href="{{ route('vendors.create') }}" class="btn btn-outline-primary mx-5">{{ __('home.new_vendor') }}</a>
        <a href="{{ route('vendor_transaction.index') }}" class="btn btn-outline-primary mx-5">{{ __('home.deposits') }}</a>

    </div>

    <div class="card-body pt-4">
        <div class="grid-margin">
            <div class="">
                <div class="panel panel-primary">
                    <div class="tab-menu-heading tab-menu-heading-boxed">
                        <div class="tabs-menu-boxed">
                            <!-- Tabs -->
                            <ul class="nav panel-tabs product-sale">
                                <li><a href="#tab1" class="active" data-bs-toggle="tab">{{  __('home.all_vendors') }}</a></li>
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
                                                <th>{{ __('home.company') }}</th>
                                                <th>{{ __('home.contact_person') }}</th>
                                                <th>{{ __('home.balance') }}</th>
                                                <th>{{ __('home.mobile') }}</th>
                                                <th>{{ __('home.address') }}</th>
                                                <th>{{ __('home.license') }}</th>
                                                <th>{{ __('home.action') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $gtotal = 0;
                                            @endphp
                                            @foreach($vendors as $obj)
                                            <tr>
                                                <td>{{$obj->id}}</td>
                                                <td>{{$obj->company}}</td>
                                                <td>{{$obj->contact_person}}</td>
                                                <td  class="@if($obj->currency->amount >0) {{ 'text-danger' }} @else {{ 'text-success' }} @endif">{{$obj->currency->currency->name}}: {{$obj->currency->amount}} @if($obj->currency->amount >0) ({{ 'payable' }})@endif</td>
                                                <td>{{$obj->mobile}}</td>
                                                <td>{{$obj->address}}</td>
                                                <td>{{$obj->license}}</td>
                                                <td>
                                                    <div class="g-2 ">
                                                        <a class="btn text-primary btn-sm" href="{{route('vendors.edit', $obj)}}" data-bs-toggle="tooltip" data-bs-original-title="{{ __('home.edit') }}"><span class="fe fe-edit fs-14"></span></a>
                                                        <a class="btn text-success btn-sm" href="{{route('vendors.statement', $obj)}}" data-bs-toggle="tooltip" data-bs-original-title="{{ __('home.statement') }}"><span class="fe fe-menu fs-14"></span></a>
                                                        <form action="{{route('vendors.destroy', $obj)}}" method="POST" class="d-none">
                                                            @method('delete')
                                                            @csrf
                                                            <button type="submit" data-bs-toggle="tooltip"  data-bs-original-title="{{ __('home.delete') }}" class="btn text-danger btn-sm"><span class="fe fe-trash-2 fs-14"></span></button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                            @php
                                                $gtotal += $obj->currency->amount;
                                            @endphp
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th></th>
                                                <th></th>
                                                <th>{{ __('home.total') }}</th>
                                                <th>{{ $gtotal }}</th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane" id="tab2">
                                <div class="table-responsive">
                                    <table id="file-datatable" class="table table-bordered table-striped text-nowrap mb-0 table-hover">
                                        <thead class="border-top">
                                            <tr>
                                                <th>{{ __('home.no') }}</th>
                                                <th>{{ __('home.name') }}</th>
                                                <th>{{ __('home.category') }}</th>
                                                <th>{{ __('home.cost') }}</th>
                                                <th>{{ __('home.quantity') }}</th>
                                                <th>{{ __('home.action') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($trashed as $obj)
                                            <tr class="border-bottom">
                                                <td>{{$obj->id}}</td>
                                                <td>{{$obj->name}}</td>
                                                <td>{{$obj->category_id}}</td>
                                                <td>{{$obj->cost}}</td>
                                                <td>{{$obj->quantity}}</td>
                                                <td>
                                                    <div class="g-2 ">

                                                        <form action="{{route('vendors.restore', $obj)}}" method="POST" class="d-inline">
                                                            @method('POST')
                                                            @csrf
                                                            <button type="submit" data-bs-toggle="tooltip"  data-bs-original-title="Restore" class="btn text-primary btn-sm"><span class="fe fe-repeat fs-14"></span></button>
                                                        </form>
                                                        <form action="{{route('vendors.forceDelete', $obj)}}" method="POST" class="d-inline">
                                                            @method('delete')
                                                            @csrf
                                                            <button type="submit" data-bs-toggle="tooltip"  data-bs-original-title="Delete Permanently" class="btn text-danger btn-sm"><span class="fe fe-trash-2 fs-14"></span></button>
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
@endsection

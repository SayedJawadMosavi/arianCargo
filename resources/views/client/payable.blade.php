@extends('layouts.app')
@section('title', 'Clients Payable')

@section('content')

<div class="card mt-4">
    @if (session()->has('success') || session()->has('error') )
        @include('layouts.partials.components.alert')
    @endif
    <div class="card-header d-flex justify-content-between">
        <h3 class="card-title">{{ __('home.client_payable') }}</h3>
        <a href="{{ route('client.create') }}" class="btn btn-primary mx-5">{{ __('home.new_client') }}</a>
        <a href="{{ route('client_transaction.index') }}" class="btn btn-primary mx-5">{{ __('home.deposits') }}</a>
    </div>

    <div class="card-body pt-4">
        <div class="grid-margin">
            <div class="">
                <div class="panel panel-primary">
                    <div class="tab-menu-heading tab-menu-heading-boxed">

                    <div class="panel-body tabs-menu-body border-0 pt-0">
                        <div class="tab-content">
                            <div class="tab-pane active" id="tab1">
                                <div class="table-responsive">
                                    <table id="file-datatable" class="table table-bordered table-striped text-nowrap mb-0 table-hover">
                                        <thead class="border-top">
                                            <tr>
                                                <th>{{ __('home.no') }}</th>
                                                <th>{{ __('home.name') }}</th>
                                                <th>{{ __('home.currency') }}</th>
                                                <th>{{ __('home.mobile') }}</th>
                                                <th>{{ __('home.address') }}</th>
                                                <th>{{ __('home.nid') }}</th>
                                                <th>{{ __('home.active') }}</th>
                                                <th>{{ __('home.action') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($clients as $obj)
                                            {{-- @dd($obj) --}}
                                            <tr>
                                                <td>{{$obj->id}}</td>
                                                <td>{{$obj->name}}</td>
                                                <td>{{isset($obj->currency) ? $obj->currency->currency->name : ''}}: {{ isset($obj->currency) ?$obj->currency->amount :''}}</td>
                                                <td>{{$obj->mobile}}</td>
                                                <td>{{$obj->address}}</td>
                                                <td>{{$obj->nid}}</td>
                                                <td>
                                                    @if($obj->active ==1 )
                                                    <span class="tag tag-radius tag-round tag-primary">{{__('home.yes') }}</span>
                                                    @else
                                                    <span class="tag tag-radius tag-round tag-red">{{__('home.no') }}</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="g-2 ">

                                                        <a class="btn text-success btn-sm" href="{{route('client.statement', $obj)}}" data-bs-toggle="tooltip" data-bs-original-title="{{ __('home.statement') }}"><span class="fe fe-menu fs-14"></span></a>

                                                    </div>
                                                </td>
                                            </tr>

                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th>{{ __('home.total') }}</th>
                                                <th colspan="7">
                                                    @foreach($sums as $key=>$value)
                                                        {{ $key }}: {{ $value }}
                                                    @endforeach
                                                </th>

                                            </tr>
                                        </tfoot>
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

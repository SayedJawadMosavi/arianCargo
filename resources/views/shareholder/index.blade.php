@extends('layouts.app')
@section('title', 'All shareholder')

@section('content')

<div class="card mt-4">
    @if (session()->has('success') || session()->has('error') )
    @include('layouts.partials.components.alert')
    @endif
    <div class="card-header">
        <h3 class="card-title">{{ __('home.shareholder') }}</h3>
        <a href="{{ route('shareholder.create') }}" class="btn btn-outline-primary mx-5">{{ __('home.new_shareholder') }}</a>
        <a href="{{ route('shareholder_transaction.index') }}" class="btn btn-outline-primary mx-5">{{ __('home.deposits') }}</a>


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
                                                    <th>{{ __('home.percentage') }}</th>
                                                    <th>{{ __('home.mobile') }}</th>
                                                    <th>{{ __('home.description') }}</th>
                                                    <th>{{ __('home.address') }}</th>
                                                    <th>{{ __('home.nid') }}</th>
                                                    <th>{{ __('home.active') }}</th>
                                                    <th>{{ __('home.action') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($shareHolder as $obj)
                                                <tr>
                                                    <td>{{$obj->id}}</td>
                                                    <td>{{$obj->name}}</td>
                                                    <td>{{$obj->percentage}}</td>
                                                    <td>{{$obj->mobile}}</td>
                                                    <td>{{$obj->description}}</td>
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
                                                            <a class="btn text-primary btn-sm" href="{{route('shareholder.edit', $obj->id)}}" data-bs-toggle="tooltip" data-bs-original-title="{{ __('home.edit') }}"><span class="fe fe-edit fs-14"></span></a>
                                                            <a class="btn text-success btn-sm" href="{{route('shareholder.statement', $obj->id)}}" data-bs-toggle="tooltip" data-bs-original-title="{{ __('home.statement') }}"><span class="fe fe-menu fs-14"></span></a>
                                                            <form action="{{route('shareholder.destroy', $obj->id)}}" method="POST" class="d-none">
                                                                @method('delete')
                                                                @csrf
                                                                <button type="submit" data-bs-toggle="tooltip" data-bs-original-title="Delete" class="btn text-danger btn-sm"><span class="fe fe-trash-2 fs-14"></span></button>
                                                            </form>
                                                        </div>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="tab-pane d-none" id="tab2">
                                    <div class="table-responsive">
                                        <table id="file-datatable" class="table table-bordered table-striped text-nowrap mb-0 table-hover">
                                            <thead class="border-top">
                                                <tr>
                                                    <th>{{ __('home.no') }}</th>
                                                    <th>{{ __('home.name') }}</th>
                                                    <th>{{ __('home.percentage') }}</th>
                                                    <th>{{ __('home.mobile') }}</th>
                                                    <th>{{ __('home.address') }}</th>
                                                    <th>{{ __('home.nid') }}</th>
                                                    <th>{{ __('home.active') }}</th>
                                                    <th>{{ __('home.action') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($trashed as $obj)
                                                <tr class="border-bottom">
                                                    <td>{{$obj->id}}</td>
                                                    <td>{{$obj->name}}</td>
                                                    <td>{{$obj->Percentage}}</td>
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

                                                            <form action="{{route('shareholder.restore', $obj)}}" method="POST" class="d-inline">
                                                                @method('POST')
                                                                @csrf
                                                                <button type="submit" data-bs-toggle="tooltip" data-bs-original-title="Restore" class="btn text-primary btn-sm"><span class="fe fe-repeat fs-14"></span></button>
                                                            </form>
                                                            <form action="{{route('shareholder.forceDelete', $obj)}}" method="POST" class="d-inline">
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
        {{-- card-body --}}
    </div>
    @endsection

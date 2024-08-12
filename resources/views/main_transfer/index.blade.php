@extends('layouts.app')
@section('title', 'All main_transfers')

@section('content')

<div class="card mt-4">
    @if (session()->has('success') || session()->has('error') )
        @include('layouts.partials.components.alert')
    @endif
    <div class="card-header d-flex justify-content-between">
        <h3 class="card-title">{{ __('home.main_transfers') }}</h3>
        @can('main_transfer.create')
        <a href="{{ route('main_transfer.create') }}" class="btn btn-primary mx-5">{{ __('home.new_main_transfer') }}</a>
        @endcan
    </div>

    <div class="card-body pt-4">
        <div class="grid-margin">
            <div class="">
                <div class="panel panel-primary">

                    <div class="panel-body tabs-menu-body border-0 pt-0">
                        <div class="tab-content">
                            <div class="tab-pane active" id="tab1">
                                <div class="table-responsive">
                                    <table id="file-datatable" class="table table-bordered table-striped text-nowrap mb-0 table-hover">
                                        <thead class="border-top">
                                            <tr>
                                                <th>{{ __('home.sn') }}</th>
                                                <th>{{ __('home.bill') }}</th>
                                                <th>{{ __('home.date') }}</th>
                                                <th>{{ __('home.stock') }}</th>
                                                <th>{{ __('home.description') }}</th>
                                                <th>{{ __('home.action') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php $total = 0; @endphp
                                            @foreach($main_transfers as $main_transfer)
                                            <tr>
                                                <td>{{$loop->iteration}}</td>
                                                <td>{{$main_transfer->bill}}</td>
                                                <td>
                                                    @if ($settings->date_type=='shamsi')
                                                    {{$main_transfer->shamsi_date}}
                                                    @else
                                                    {{$main_transfer->miladi_date}}
                                                    @endif
                                                </td>
                                                <td>{{$main_transfer->stock->name}}</td>
                                                <td>{{$main_transfer->description}}</td>
                                                <td>
                                                    <div class="g-2 ">
                                                        <a class="btn text-primary btn-sm " href="{{route('main_transfer.detail.get', $main_transfer)}}" data-bs-toggle="tooltip" data-bs-original-title="{{ __('home.detail') }}"><span class="fe fe-menu fs-14"></span></a>
                                                        <a class="btn text-success btn-sm d-none" href="{{route('main_transfer.edit', $main_transfer)}}" data-bs-toggle="tooltip" data-bs-original-title="{{ __('home.edit') }}"><span class="fe fe-edit fs-14"></span></a>

                                                        {{-- @can('main_transfer.delete')
                                                        <form action="{{route('main_transfer.destroy', $main_transfer)}}" method="POST" class="d-none">
                                                            @method('delete')
                                                            @csrf
                                                            <button type="submit" data-bs-toggle="tooltip"  data-bs-original-title="Delete" class="btn text-danger btn-sm"><span class="fe fe-trash-2 fs-14"></span></button>
                                                        </form>
                                                        @endcan --}}
                                                    </div>
                                                </td>
                                            </tr>
                                            @php $total += $main_transfer->quantity; @endphp
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

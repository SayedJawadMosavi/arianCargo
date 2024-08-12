@extends('layouts.app')
@section('title', 'All stocks')

@section('content')

<div class="card mt-4">
    @if (session()->has('success') || session()->has('error') )
        @include('layouts.partials.components.alert')
    @endif
    <div class="card-header">
        <h3 class="card-title">{{ __('home.stocks') }}</h3>
        <a href="{{ route('stock.create') }}" class="btn btn-primary mx-5">{{ __('home.new_stock') }}</a>
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
                                                <th>{{ __('home.name') }}</th>
                                                <th>{{ __('home.contact_person') }}</th>
                                                <th>{{ __('home.mobile') }}</th>
                                                <th>{{ __('home.address') }}</th>
                                                <th>{{ __('home.action') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php $total = 0; @endphp
                                            @foreach($stocks as $stock)
                                            <tr>
                                                <td>{{$loop->iteration}}</td>
                                                <td>{{$stock->name}}</td>
                                                <td>{{$stock->contact_person}}</td>
                                                <td>{{$stock->mobile}}</td>
                                                <td>{{$stock->address}}</td>
                                                <td>
                                                    <div class="g-2 ">
                                                        <a class="btn text-primary btn-sm" href="{{route('stock.edit', $stock)}}" data-bs-toggle="tooltip" data-bs-original-title="Edit"><span class="fe fe-edit fs-14"></span></a>
                                                        <a class="btn text-secondary btn-sm" href="{{route('stock.products', $stock)}}" data-bs-toggle="tooltip" data-bs-original-title="{{ __('home.products') }}"><span class="fe fe-menu fs-14"></span></a>
                                                        <form action="{{route('stock.destroy', $stock)}}" method="POST" class="d-none">
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
                            <div class="tab-pane d-none" id="tab2">
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
                                            @foreach($trashed as $stock)
                                            <tr class="border-bottom">
                                                <td>{{$stock->id}}</td>
                                                <td>{{$stock->name}}</td>
                                                <td>{{$stock->category_id}}</td>
                                                <td>{{$stock->cost}}</td>
                                                <td>{{$stock->quantity}}</td>
                                                <td>
                                                    <div class="g-2 ">

                                                        <form action="{{route('stock.restore', $stock)}}" method="POST" class="d-inline">
                                                            @method('POST')
                                                            @csrf
                                                            <button type="submit" data-bs-toggle="tooltip"  data-bs-original-title="Restore" class="btn text-primary btn-sm"><span class="fe fe-repeat fs-14"></span></button>
                                                        </form>
                                                        <form action="{{route('stock.forceDelete', $stock)}}" method="POST" class="d-inline">
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

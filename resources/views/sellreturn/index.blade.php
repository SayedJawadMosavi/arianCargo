@extends('layouts.app')
@section('title', 'All returns')

@section('content')

<div class="card mt-4">
    @if (session()->has('success') || session()->has('error') )
    @include('layouts.partials.components.alert')
    @endif
    <div class="card-header">
        <h3 class="card-title">{{ __('home.returns') }}</h3>
        <a href="{{ route('sellreturn.create') }}" class="btn btn-outline-primary mx-5">{{ __('home.new_return') }}</a>
    </div>

    <div class="card-body pt-4">
        <div class="col-sm-12 my-3">
            @php $data = 'hey'; @endphp
            <form class="" action="{{ route('sellreturn.filter') }}" method="POST">
                <x-date-component :data="$data" />
            </form>

        </div>
        <div class="grid-margin">
            <div class="">
                <div class="panel panel-primary">
                    <div class="tab-menu-heading tab-menu-heading-boxed">
                        <div class="tabs-menu-boxed">
                            <!-- Tabs -->
                            <ul class="nav panel-tabs return-sale">
                                <li><a href="#tab1" class="active" data-bs-toggle="tab">{{ __('home.all_returns') }}</a></li>
                                <li><a href="#tab2" data-bs-toggle="tab" class="text-dark">{{ __('home.trashed') }}</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="panel-body tabs-menu-body border-0 pt-0">
                        <div class="tab-content">
                            <div class="tab-pane active mt-4" id="tab1">
                                <div class="table-responsive">
                                    <table id="file-datatable" class="table table-bordered table-striped text-nowrap mb-0 table-hover">
                                        <thead class="border-top">
                                            <tr>
                                                <th>{{ __('home.sn') }}</th>
                                                <th>{{ __('home.date') }}</th>
                                                <th>{{ __('home.sell_id') }}</th>
                                                <th>{{ __('home.product') }}</th>
                                                <th>{{ __('home.unit') }}</th>
                                                <th>{{ __('home.quantity') }}</th>
                                                <th>{{ __('home.cost') }}</th>
                                                <th>{{ __('home.description') }}</th>
                                                <th>{{ __('home.action') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($returns as $return)
                                            <tr>
                                                <td>{{$loop->iteration}}</td>
                                                <td>
                                                    @if ($settings->date_type=='shamsi')
                                                    {{$return->shamsi_date}}
                                                    @else
                                                    {{$return->miladi_date}}
                                                    @endif
                                                </td>
                                                <td>{{$return->sell_id}}</td>
                                                <td>{{$return->stock_product->product->name}}</td>
                                                <td>{{$return->stock_product->product->unit->name}}</td>
                                                <td>{{number_format($return->quantity)}}</td>
                                                <td>{{number_format($return->cost)}}</td>
                                                <td>{{$return->description}}</td>
                                                <td>
                                                    <div class="g-2 ">
                                                        <a class="btn text-primary btn-sm" href="{{route('sellreturn.edit', $return->id)}}" data-bs-toggle="tooltip" data-bs-original-title="{{ __('home.edit') }}"><span class="fe fe-edit fs-16"></span></a>
                                                        <form action="{{route('sellreturn.destroy', $return->id)}}" method="POST" class="">
                                                            @method('delete')
                                                            @csrf
                                                            <button type="submit" data-bs-toggle="tooltip" data-bs-original-title="{{ __('home.delete') }}" class="btn text-danger btn-sm"><span class="fe fe-trash-2 fs-16"></span></button>
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
                                            <th>{{ __('home.sn') }}</th>
                                                <th>{{ __('home.date') }}</th>
                                                <th>{{ __('home.sell_id') }}</th>
                                                <th>{{ __('home.product') }}</th>
                                                <th>{{ __('home.unit') }}</th>
                                                <th>{{ __('home.quantity') }}</th>
                                                <th>{{ __('home.cost') }}</th>
                                                <th>{{ __('home.description') }}</th>
                                                <th>{{ __('home.action') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($trashed as $return)
                                            <tr class="border-bottom">
                                            <td>{{$loop->iteration}}</td>
                                                <td>
                                                    @if ($settings->date_type=='shamsi')
                                                    {{$return->shamsi_date}}
                                                    @else
                                                    {{$return->miladi_date}}
                                                    @endif
                                                </td>
                                                <td>{{$return->sell_id}}</td>
                                                <td>{{$return->stock_product->product->name}}</td>
                                                <td>{{$return->stock_product->product->unit->name}}</td>
                                                <td>{{number_format($return->quantity)}}</td>
                                                <td>{{number_format($return->cost)}}</td>
                                                <td>{{$return->description}}</td>
                                                    <div class="g-2 ">

                                                        <form action="" method="POST" class="d-inline">
                                                            @method('POST')
                                                            @csrf
                                                            <button type="submit" data-bs-toggle="tooltip" data-bs-original-title="Restore" class="btn text-primary btn-sm"><span class="fe fe-repeat fs-14"></span></button>
                                                        </form>
                                                        <form action="" method="POST" class="d-inline">
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

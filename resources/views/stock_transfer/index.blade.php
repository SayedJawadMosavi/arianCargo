@extends('layouts.app')
@section('title', 'All stock_transfers')

@section('content')

<div class="card mt-4">
    @if (session()->has('success') || session()->has('error') )
        @include('layouts.partials.components.alert')
    @endif
    <div class="card-header">
        <h3 class="card-title">{{ __('home.stock_transfers') }}</h3>
        <a href="{{ route('stock_transfer.create') }}" class="btn btn-outline-primary mx-5">{{ __('home.new_stock_transfer') }}</a>
    </div>

    <div class="card-body pt-4">
        <div class="grid-margin">
            <div class="">
                <div class="panel panel-primary">
                    <div class="tab-menu-heading tab-menu-heading-boxed">
                        <div class="tabs-menu-boxed">
                            <!-- Tabs -->
                            <ul class="nav panel-tabs stock_transfer-sale">
                                <li><a href="#tab1" class="active" data-bs-toggle="tab">{{  __('home.all_stock_transfers') }}</a></li>
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
                                                <th>{{ __('home.date') }}</th>
                                                <th>{{ __('home.from_stock') }}</th>
                                                <th>{{ __('home.to_stock') }}</th>
                                                <th>{{ __('home.product') }}</th>
                                                <th>{{ __('home.quantity') }}</th>
                                                <th>{{ __('home.description') }}</th>
                                                {{-- <th>{{ __('home.action') }}</th> --}}
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php $total = 0; @endphp
                                            @foreach($stock_transfers as $stock_transfer)
                                            <tr>
                                                <td>{{$loop->iteration}}</td>
                                                <td>
                                                    @if ($settings->date_type=='shamsi')
                                                    {{$stock_transfer->shamsi_date}}
                                                    @else
                                                    {{$stock_transfer->miladi_date}}
                                                    @endif
                                                </td>
                                                <td>{{$stock_transfer->sender->name}}</td>
                                                <td>{{$stock_transfer->receiver->name}}</td>
                                                <td>{{$stock_transfer->sender_product->product->name}}</td>
                                                <td>{{number_format( $qty = $stock_transfer->stockTransfer->sum('quantity') )}} </td>
                                                <td>{{$stock_transfer->description}}</td>
                                                {{-- <td>
                                                    <div class="g-2 d-none">
                                                        <a class="btn text-primary btn-sm" href="{{route('stock_transfer.edit', $stock_transfer)}}" data-bs-toggle="tooltip" data-bs-original-title="Edit"><span class="fe fe-edit fs-14"></span></a>
                                                        <form action="{{route('stock_transfer.destroy', $stock_transfer)}}" method="POST" class="d-none">
                                                            @method('delete')
                                                            @csrf
                                                            <button type="submit" data-bs-toggle="tooltip"  data-bs-original-title="Delete" class="btn text-danger btn-sm"><span class="fe fe-trash-2 fs-14"></span></button>
                                                        </form>
                                                    </div>
                                                </td> --}}
                                            </tr>
                                            @php $total += $qty; @endphp
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th>{{ __('home.total') }}</th>
                                                <th>{{ $total }}</th>
                                                <th></th>
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

@extends('layouts.app')
@section('title', 'Main Transfer Detail')

@section('content')

<div class="card mt-4">
    @if (session()->has('success') || session()->has('error') )
    @include('layouts.partials.components.alert')
    @endif
    <div class="card-header">
        <h3 class="card-title">{{ __('home.main_transfer_details') }}</h3>
        <div class=" mx-5 alert alert-success" id="success-message error-message"></div>

    </div>

    <div class="card-body pt-4">
        <div class="grid-margin">
            <div class="">
                <div class="panel panel-primary">

                    <div class="panel-body tabs-menu-body border-0 pt-4">
                        <div class="tab-content">
                            <div class="tab-pane active" id="tab1">
                                <div class="table-responsive">
                                    <table id="file-datatable" class="table table-bordered table-striped text-nowrap mb-0 table-hover">
                                        <thead class="border-top">
                                            <tr>
                                                <th class="d-none">{{ __('home.no') }}</th>
                                                <th>{{ __('home.no') }}</th>
                                                <th>{{ __('home.product') }}</th>
                                                <th>{{ __('home.model') }}</th>
                                                <th>{{ __('home.quantity') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php $c =1; $total = 0; $total_quantity = 0; @endphp

                                            @foreach($details as $detail)
                                            <tr>
                                                <td class="d-none">{{$detail->id}}</td>
                                                <td>{{$c++}}</td>
                                                <td>{{$detail->product->name}}</td>
                                                <td>{{$detail->product->model}}</td>
                                                <td>{{$detail->quantity}}</td>

                                            </tr>

                                            @php $c++;
                                            $total += $detail->quantity * $detail->cost;
                                            $total_quantity += $detail->quantity;

                                            @endphp

                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr>

                                                <th></th>
                                                <th></th>
                                                <th>{{ __('home.total') }}</th>
                                                <th>{{ $total_quantity }}</th>
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

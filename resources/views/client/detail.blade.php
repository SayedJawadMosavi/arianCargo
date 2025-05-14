@extends('layouts.app')
@section('title', 'sells Detail')

@section('content')

<div class="card mt-4">
    @if (session()->has('success') || session()->has('error') )
    @include('layouts.partials.components.alert')
    @endif
    <div class="card-header">
        <h3 class="card-title">{{ __('home.sell_details') }}</h3>


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
                                                <th>{{ __('home.items') }}</th>

                                                <th>{{ __('home.quantity') }}</th>
                                                <th>{{ __('home.dimensions') }}</th>
                                                <th>{{__('home.type')}}</th>

                                                <th>{{ __('home.value') }}</th>

                                            </tr>
                                        </thead>

                                        <tbody>
                                            @php $c =1; @endphp

                                            @foreach($details as $detail)
                                            <tr>
                                                <td class="d-none">{{$detail->id}}</td>
                                                <td>{{$c++}}</td>
                                                <td>{{$detail->item_name}}</td>


                                                <td>{{ number_format($detail->quantity) }}</td>
                                                <td>{{ ($detail->cbm) }}</td>
                                                <td>{{ ($detail->type) }}</td>
                                                <td>{{ ($detail->item_value) }}</td>


                                            </tr>
                                            @php $c++; @endphp

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



@section('pagescript')


@endsection

@extends('layouts.app')
@section('title', 'Due Client Report')

@section('content')

<div class="card mt-4">
    {{-- @dd(session()->has('success')) --}}
    @if (session()->has('success') || session()->has('error') )
    @include('layouts.partials.components.alert')
    @endif

    <div class="card-header ">
        <h3 class="card-title">{{ __('home.due_clients_report') }}</h3>
    </div>
    <div class="card-body pt-4">
        <div class="col-sm-12 my-3">
            <form class="d-none" action="{{ route('report.due_clients.post') }}" method="POST">
                {{-- <x-date-component :data="$categories"/> --}}
                @csrf
                @method('POST')
                <div class="form-row align-items-center my-5 offset-md-1">

                    <div class="col-xl-3">
                        <label for="validationServer04">{{ __('home.clients') }}</label>
                        <select class="form-selects form-control" id="validationServer04" aria-describedby="validationServer04Feedback" required name="client_id">
                            <option value="all">{{ __('home.all') }}</option>
                            @foreach($clients as $obj)
                            <option value="{{$obj->id}}"> {{ $obj->name }}</option>
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

                    <th>{{ __('home.client') }}</th>
                    <th>{{ __('home.mobile') }}</th>
                    <th>{{ __('home.currency') }}</th>
                    <th>{{ __('home.quantity') }}</th>

                    </tr>
                </thead>

                @php $gquantity =0; @endphp
                <tbody>
                    @isset($logs)

                    @forelse($logs as $obj)

                    @if($obj->amount < 0) <tr>
                        <td>{{$loop->iteration}}</td>

                        <td>{{$obj->client->name}}</td>
                        <td>{{$obj->client->mobile}}</td>
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


            </table>
        </div>
    </div>
    {{-- card-body --}}
</div>
@endsection

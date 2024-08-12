@extends('layouts.app')
@section('title', 'client Statement')

@section('content')

<div class="card mt-4">
    {{-- @dd(session()->has('success')) --}}
    @if (session()->has('success') || session()->has('error') )
    @include('layouts.partials.components.alert')
    @endif

    <div class="card-header ">
        <h3 class="card-title">{{ __('home.statement') }} - {{ $client->name }} </h3>
        <a href="{{ route('client.create') }}" class="btn btn-primary mx-5">{{ __('home.new_client') }}</a>
    </div>

    <div class="card-body pt-4">
        @php $data = 'hey'; @endphp
        <form class="" action="{{ route('clientstatement.filter') }}" method="POST">
            <x-date-component :data="$data" />
            <input type="hidden" value="{{$client->id}}" name="client_id">
        </form>
        <table class="table table-bordered">
            {{-- @dd($client->currency) --}}
            @isset($client->client_currency)
                @foreach ($client->client_currency as $cur )
                    <tr>
                        <th>{{$cur->currency->name}}</th>
                        <td>{{$cur->amount}}</td>
                    </tr>
                @endforeach
            @endisset
        </table>
        {{-- @dD('he') --}}
        <div class="table-responsive">
            <table id="datatable" class="table table-bordered table-striped text-nowrap mb-0 table-hover">
                <thead>
                    <tr>
                        <th>{{ __('home.date') }}</th>
                        <th>{{ __('home.description') }}</th>
                        <th>{{ __('home.currency') }}</th>
                        <th>{{ __('home.clearance_date') }}</th>
                        <th>{{ __('home.clearance_description') }}</th>
                        <th>{{ __('home.paid') }}</th>
                        <th>{{ __('home.received') }}</th>
                        <th>{{ __('home.balance') }}</th>
                        <th>{{ __('home.action') }}</th>

                    </tr>
                </thead>
                <tbody>
                    @php $total_deposit = 0; $total_witdtraw = 0; @endphp
                    @foreach($logs as $obj)
                    <tr @if($obj->clearance_description != null) class="bg bg-success" @endif>
                        <td>
                            @if ($settings->date_type=='shamsi')
                            {{$obj->shamsi_date}}
                            @else
                            {{$obj->miladi_date}}
                            @endif
                        </td>
                        <td>{{$obj->description}}</td>
                        <td>{{$obj->client_currency->currency->name}}</td>
                        <td>
                            @if ($settings->date_type=='shamsi')
                            {{$obj->clearance_date_shamsi}}
                            @else
                            {{$obj->clearance_date_miladi}}
                            @endif
                        </td>
                        <td>{{$obj->clearance_description}}</td>
                        <td>@if($obj->type == 'deposit' ){{number_format($obj->amount, 2)}} @endif</td>
                        <td>@if($obj->type == 'withdraw' ){{number_format($obj->amount, 2)}} @endif</td>
                        <td>{{number_format($obj->available, 2)}} </td>

                        <td class="d-flex">
                            <div class="">
                                @if($obj->clearance_description==null)
                                <a class="" data-bs-effect="effect-sign" data-bs-toggle="modal" href="#modaldemo{{$obj->id}}"><span class="badge bg-primary"> <i class="fe fe-plus "></i></span></a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    <div class="modal fade " id="modaldemo{{$obj->id}}">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h6 class="modal-title">{{ __('home.clearance_message') }}</h6><button aria-label="Close" class="btn-close" data-bs-dismiss="modal"><span aria-hidden="true">&times;</span></button>
                                </div>
                                <form action="{{ route('client_clearance.store') }}" method="POST" class="d-inline">
                                    @method('POST')
                                    @csrf
                                    <div class="modal-body">
                                        <div class="form-row mb-3">
                                            <input type="hidden" name="id" value="{{$obj->id}}">
                                            <input type="hidden" name="client_id" value="{{$obj->client_id}}">
                                            @if ($settings->date_type=='shamsi')
                                            <div class="col-xl-12 mb-3">
                                                <label for="validationServer01">{{ __('home.date') }}</label>
                                                <input type="text" class="form-control form-control @error('date') {{'is-invalid'}} @enderror" name="clearance_date_shamsi" autocomplete="off" id="dates" value="{{isset($obj) ? $obj->clearance_date_shamsi :  date('Y-m-d')}}">

                                                @error('date')
                                                <div id="" class="invalid-feedback">{{$message}}</div>
                                                @enderror
                                            </div>
                                            @else
                                            <div class="col-xl-12 mb-3">
                                                <label for="validationServer01">{{ __('home.date') }}</label>
                                                <input type="date" class="form-control @error('date') {{'is-invalid'}} @enderror" id="date" name="clearance_date_miladi" value="{{ isset($obj) ? $obj->clearance_date_miladi : date('Y-m-d') }}">
                                                @error('date')
                                                <div id="" class="invalid-feedback">{{$message}}</div>
                                                @enderror
                                            </div>
                                            @endif

                                            <div class="col-xl-12 col-sm-12 mb-3">
                                                <label class="" for="inlineFormInputGroup">{{__('home.description')}}</label>
                                                <textarea name="clearance_description" class="form-control" id="description"></textarea>

                                            </div>

                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('home.cancel') }}</button>
                                        <button type="submit" class="btn btn-primary">{{ __('home.save') }}</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    @php
                    if ($obj->type == 'deposit') {
                    $total_deposit += $obj->amount;
                    } else {
                    $total_witdtraw += $obj->amount;
                    }
                    @endphp

                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th>{{ __('home.total') }}</th>
                        <th>{{ $total_deposit }}</th>

                        <th>{{ $total_witdtraw }}</th>

                        <th> {{ __('home.balance') }}: {{$total_deposit - $total_witdtraw}}</th>
                        <th></th>

                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    {{-- card-body --}}
</div>

@endsection

<script>
    function update($id) {
        // alert('hi');
        $('.update_' + $id).submit();
    }
</script>

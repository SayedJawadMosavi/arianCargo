@extends('layouts.app')
@section('title', 'shareholder Statement')

@section('content')

<div class="card mt-4">
    {{-- @dd(session()->has('success')) --}}
    @if (session()->has('success') || session()->has('error') )
        @include('layouts.partials.components.alert')
    @endif

    <div class="card-header ">
        <h3 class="card-title">{{ __('home.statement') }} - {{ $shareholder->name }} </h3>
        <a href="{{ route('shareholder.create') }}" class="btn btn-outline-primary mx-5">{{ __('home.new_shareholder') }}</a>
    </div>

    <div class="card-body pt-4">

        <div class="col-sm-12 my-3">
            @php $data = 'hey'; @endphp
            <form class="" action="{{ route('shareholder.statement.filter', $shareholder->id) }}" method="POST">
                {{-- <x-table-component :data="$myCollection" /> --}}
                <x-date-component :data="$data" />
            </form>

        </div>

        <table class="table table-bordered">
            @foreach ($shareholder->currency as $cur )
            <tr>
                <th>{{$cur->currency->name}}</th>
                <td>{{number_format($cur->amount)}}</td>
            </tr>

            @endforeach
        </table>

        <div class="table-responsive">
            <table id="file-datatable" class="table table-bordered table-striped text-nowrap mb-0 table-hover">
                <thead>
                    <tr>
                        <th>{{ __('home.date') }}</th>
                        <th>{{ __('home.name') }}</th>
                        <th>{{ __('home.description') }}</th>
                        <th>{{ __('home.currency') }}</th>
                        <th>{{ __('home.deposit') }}</th>
                        <th>{{ __('home.withdraw') }}</th>
                        <th>{{ __('home.balance') }}</th>

                    </tr>
                </thead>
                <tbody>
                    @foreach($logs as $obj)
                    <tr>
                        <td>
                            @if ($settings->date_type=='shamsi')
                            {{$obj->shamsi_date}}
                            @else
                            {{$obj->miladi_date}}
                            @endif
                        </td>
                        <td>{{$shareholder->name}}</td>
                        <td>{{$obj->description}}</td>
                        <td>{{$obj->shareholder_currency->currency->name}}</td>
                        <td>@if($obj->type == 'deposit' ){{number_format($obj->amount, 2)}} @endif</td>
                        <td>@if($obj->type == 'withdraw' ){{number_format($obj->amount, 2)}} @endif</td>
                        <td>{{number_format($obj->available, 2)}} </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    {{-- card-body --}}
</div>
@endsection

<script>
    function update($id){
        // alert('hi');
        $('.update_'+$id).submit();
    }

</script>

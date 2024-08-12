@extends('layouts.app')
@section('title', 'Journal')

@section('content')

<div class="card mt-4">
    {{-- @dd(session()->has('success')) --}}
    @if (session()->has('success') || session()->has('error') )
    @include('layouts.partials.components.alert')
    @endif

    <div class="card-header ">
        <h3 class="card-title">{{ __('home.journal') }} </h3>
    </div>

    <div class="card-body pt-4 table-responsive">

        <div class="col-sm-12 my-3">
            @php $data = 'hey'; @endphp
            <form class="" action="{{ route('journal.post') }}" method="POST">
                <x-date-component :data="$data" />
            </form>

        </div>
        <table class="table table-bordered">
            <tbody>
                <tr class="table-success">
                    @foreach($deposit as $k=>$v)
                    <td> {{__('home.in')}} {{$k}}: {{number_format($v)}}</td>
                    @endforeach
                </tr>
                <tr class="table-warning">
                    @foreach($withdraw as $k=>$v)
                    <td> {{ __('home.out')}} {{$k}}: {{number_format($v)}}</td>
                    @endforeach
                </tr>
                {{-- <tr>
                    <td colspan="2"><strong>Balance:</strong></td>
                </tr> --}}
                <tr class="table-primary">
                @foreach($deposit as $currency => $amount)
                <?php
                $withdrawal = $withdraw[$currency] ?? 0;
                $balance = $amount - $withdrawal;
                ?>

                    <td class="fw-bold">{{ __('home.balance')}} {{$currency}}:  {{ number_format($balance) }}</td>

                    @endforeach
                </tr>
            </tbody>
        </table>
        <div class="table-responsive">
            <table id="file-datatable" class="table table-bordered table-striped text-nowrap mb-0 table-hover">
                <thead>
                    <tr>
                        <th>{{ __('home.date') }}</th>
                        <th>{{ __('home.description') }}</th>
                        <th>{{ __('home.currency') }}</th>
                        <th>{{ __('home.deposit') }}</th>
                        <th>{{ __('home.withdraw') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($logs as $obj)
                    <tr>
                        @if ($settings->date_type=='shamsi')
                        <td>{{$obj->shamsi_date}}</td>
                        @else
                        <td>{{$obj->miladi_date}}</td>
                        @endif
                        <td>{{$obj->description}}</td>
                        <td>{{$obj->account->currency->name}}</td>
                        <td>@if($obj->type == 'deposit' ){{number_format($obj->amount, 2)}} @endif</td>
                        <td>@if($obj->type == 'withdraw' ){{number_format($obj->amount, 2)}} @endif</td>
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
    function update($id) {
        // alert('hi');
        $('.update_' + $id).submit();
    }
</script>

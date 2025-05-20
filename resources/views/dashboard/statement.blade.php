@extends('layouts.app')
@section('title', 'Journal')

@section('content')
<div class="card shadow-sm border-0 mt-4">
    {{-- Success/Error Message --}}
    @if (session()->has('success') || session()->has('error'))
        @include('layouts.partials.components.alert')
    @endif

    {{-- Header --}}
    <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0">
            <i class="fas fa-book me-2"></i> {{ __('home.journal') }}
        </h5>
    </div>

    {{-- Body --}}
    <div class="card-body pt-4">

        {{-- Date Filter --}}
        <div class="mb-4">
            @php $data = 'hey'; @endphp
            <form action="{{ route('journal.post') }}" method="POST" class="row g-3">
                @csrf
                <div class="col-12">
                    <x-date-component :data="$data" />
                </div>
            </form>
        </div>

        {{-- Summary Table --}}
      <div class="table-responsive mb-4">
    <table class="table table-bordered text-center align-middle mb-0">
        <thead class="table-warning">
            <tr>
                <th>{{ __('home.currency') }}</th>
                <th class="text-success"><i class="fa fa-arrow-down"></i> {{ __('home.in') }}</th>
                <th class="text-danger"><i class="fa fa-arrow-up"></i> {{ __('home.out') }}</th>
                <th class="text-primary"><i class="fa fa-wallet"></i> {{ __('home.balance') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($deposit as $currency => $amount)
                @php
                    $out = $withdraw[$currency] ?? 0;
                    $balance = $amount - $out;
                @endphp
                <tr>
                    <td class="fw-bold">{{ $currency }}</td>
                    <td class="text-success">{{ number_format($amount) }}</td>
                    <td class="text-danger">{{ number_format($out) }}</td>
                    <td class="text-primary fw-bold">{{ number_format($balance) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

        {{-- Journal Log Table --}}
        <div class="table-responsive">
            <table id="file-datatable" class="table table-bordered table-striped table-hover align-middle text-center">
                <thead class="table-light">
                    <tr>
                        <th>{{ __('home.date') }}</th>
                        <th>{{ __('home.description') }}</th>
                        <th>{{ __('home.currency') }}</th>
                        <th class="text-success">{{ __('home.deposit') }}</th>
                        <th class="text-danger">{{ __('home.withdraw') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($logs as $obj)
                        <tr>
                            <td>
                                {{ $settings->date_type == 'shamsi' ? $obj->shamsi_date : $obj->miladi_date }}
                            </td>
                            <td>{{ $obj->description }}</td>
                            <td>{{ $obj->account->currency->name }}</td>
                            <td class="text-success">
                                @if($obj->type == 'deposit')
                                    {{ number_format($obj->amount, 2) }}
                                @endif
                            </td>
                            <td class="text-danger">
                                @if($obj->type == 'withdraw')
                                    {{ number_format($obj->amount, 2) }}
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div> {{-- card-body --}}
</div>

@endsection

<script>
    function update($id) {
        // alert('hi');
        $('.update_' + $id).submit();
    }
</script>

@extends('layouts.app')

@section('title', 'Sell Invoice')
@section('content')

<style>
    .footer-image {
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 100px;
        background: url('/images/footer.jpeg') no-repeat center bottom;
        background-size: contain;
    }

    table,
    table th,
    table td {
        border: 1px solid black !important;
    }
</style>

<div class="card mt-4" id="printable-content">
    <div class="card-body no-break" style="padding-bottom: 150px">
        <div class="row">
            <div class="col-lg-12 mb-5">
                <center class="text-blue-d2">
                    <img src="{{ asset('/logo/logo.png') }}" width="250px" class="img-fluid" alt="logo">
                    <h5 class="text-80 font-weight-bold">CUSTOMS CLEARING, INT, FREIGHT FORWARGING</h5>
                    <h6 class="text-80 font-weight-bold">IMPORT, EXPORT & DOOR TO DOOR SERVICES</h6>
                    <h6 class="text-80 font-weight-bold">AUTHORISED BY THE GOVT OF AFGHANISTAN</h6>
                </center>
            </div>
        </div>

        <div class="col-md-12">
            <table class="table table-bordered mb-0 text-nowrap">
                <tr>
                    <th colspan="2" class="text-center text-white" style="background: #29a1e6 !important;">Sender Information</th>
                    <th colspan="2" class="text-center text-white" style="background: #29a1e6 !important;">Receiver Information</th>
                </tr>
                  <thead>
                        <tr>
                            <th class="text-center text-white" style="background: #29a1e6 !important;">{{ __('home.bill') }}   : {{$sell->bill}}</th>
                            <th class="text-end text-white" style="background: #29a1e6 !important;">Invoice  :{{ $sell->number}}</th>
                            <th class="text-end text-white" style="background: #29a1e6 !important;"></th>
                            <th class="text-end text-white" style="background: #29a1e6 !important;"></th>

                        </tr>
                    </thead>
                <tr>
                    <th >Shipper Name</th>
                    <td>{{ $sell->client->name }}</td>
                    <th >Receiver Name</th>
                    <td>{{ $sell->receiver->name ?? '' }}</td>
                </tr>
                <tr>
                    <th>{{ __('home.address') }}</th>
                    <td>{{ $sell->client->address }}</td>
                    <th>{{ __('home.address') }}</th>
                 <td>
    {{ $sell->receiver->address ?? '' }}
    @if(!empty($sell->receiver->zipcode))
        , ZIPCode: {{ $sell->receiver->zipcode }}
    @endif
</td>
                </tr>
                <tr>
                    <th>{{ __('home.mobile') }}</th>
                    <td>{{ $sell->client->mobile }}</td>
                    <th>{{ __('home.mobile') }}</th>
                    <td>{{ $sell->receiver->mobile ?? '' }}</td>
                </tr>
                <tr>
                    <th>{{ __('home.date') }}</th>
                    <td>{{ $sell->miladi_date ?? '' }}</td>
                    <th>{{ __('home.country') }}</th>
                    <td>{{ $sell->receiver->country->name ?? '' }}</td>
                </tr>
            </table>

            <div class="table-responsive push">
                <table class="table table-bordered table-hover mb-0 text-nowrap">

                    <thead>
                        <tr>
                            <th class="text-center text-white" style="background: #29a1e6 !important;">{{ __('home.sn') }}</th>
                            <th class="text-end text-white" style="background: #29a1e6 !important;">{{ __('home.items') }}</th>
                            <th class="text-center text-white" style="background: #29a1e6 !important;">{{ __('home.quantity') }}</th>
                            <th class="text-end text-white" style="background: #29a1e6 !important;">{{ __('home.dimensions') }}</th>
                            <th class="text-center text-white" style="background: #29a1e6 !important;">{{ __('home.value') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($sell->detail as $obj)
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td class="text-center">{{ $obj->item_name }}</td>
                            <td class="text-end">{{ $obj->quantity }}</td>
                            <td class="text-end">{{ $obj->cbm }}</td>
                            <td class="text-end">{{ $obj->item_value }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <table class="table table-hover mt-3">
                <thead>
                    <tr>
                        @if ($settings->currency_id== $sell->currency_id)

                        <th>Currency: {{ $sell->currency->name }}</th>
                        <th>Total Weight: {{ $sell->total_weight }}</th>
                        <th>Total Cost: {{ $sell->new_total }}</th>
                        <th>Total Paid: {{ $sell->paid }}</th>
                        <th>Total Reminder: {{ $sell->new_balance }}</th>
                        @else
                        <th>Currency: {{ $sell->currency->name }}</th>

                        <th>Total Weight: {{ $sell->total_weight }}</th>
                        <th>Total Cost: {{ $sell->equalent_total }}</th>
                        <th>Total Paid: {{ $sell->paid_equalent }}</th>
                        <th>Total Reminder: {{ $sell->equalent_balance }}</th>

                        @endif
                    </tr>
                </thead>
            </table>

            <div class="row bg-white mt-4 no-break">
                <div class="col-md-12">
                    <div>
                        <div style="white-space: pre-wrap; font-family: inherit;">
I/we {{ $sell->client->name }} hereby undertake that the above-mentioned particulars are true and correct
as per my statement and there is nothing dangerous, antiques, narcotics, liquid or anything likely to
cause damage. If anything found I/we will be fully responsible.

1. Any taxes at the destination will be paid by the consignee.
2. Company is not responsible for any kind of Breakage and Damage. Therefore we never entertain any
claim in case of breakage and damage.
3. If anything found objectionable (Drugs / Narcotics) during examination company will detain the
whole cargo and case should refer to relevant authorities.

Shipper Signature: ……………………………………………        Thumb Impression: ________________
CNIC/PP No: ………………………………………………………………

<center><strong>For Pieces Trading Company</strong></center>
                        </div>
                        <p class="mt-4" style="font-weight: bold; border: 1px solid orange; padding: 10px;">
Thank you for choosing Ariana Express and cargo services.
This is just to inform you that we have successfully received your shipment and it is being processed
and will be shipped to consignee. For further information please contact us.
                        </p>
                        <address class="mt-3">
                            Address: {{$sell->branchs->address}}<br>
                            Contact Number: {{$sell->branchs->mobile1}}   -  {{$sell->branchs->mpbile2}}<br>
                            Name: {{$sell->branchs->contact_person}}
                        </address>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card-footer text-end d-print-none">
        <button type="button" class="btn btn-primary mb-1" onclick="printCardContent();">
            <i class="si si-wallet"></i> {{ __('home.print') }}
        </button>
    </div>
</div>

@endsection

@section('pagescript')
<script>
    function printCardContent() {
        window.print();
    }
</script>
@endsection

<style>
    @media print {
        body {
            margin: 0.5cm;
            zoom: 90%;
        }

        .no-break {
            page-break-inside: avoid;
        }

        table {
            page-break-inside: avoid;
        }

        tr, td, th {
            page-break-inside: avoid !important;
            page-break-after: auto;
        }

        .card-body {
            padding: 0 !important;
        }
    }
</style>

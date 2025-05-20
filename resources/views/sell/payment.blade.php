@extends('layouts.app')
@section('title', 'All Payments')

@section('content')

<div class="card mt-4">
    @if (session()->has('success') || session()->has('error') )
    @include('layouts.partials.components.alert')
    @endif
    <div class="card-header d-flex justify-content-between">
        <h3 class="card-title">{{ __('home.payment') }}</h3>
        <div class=" mx-5 alert alert-success success-message error-message" id=""></div>

    </div>

    <div class="card-body pt-4">
        <div class="grid-margin">
            <div class="">
                <div class="panel panel-primary">
                    {{-- <div class="tab-menu-heading tab-menu-heading-boxed">
                        <div class="tabs-menu-boxed">
                            <!-- Tabs -->
                            <ul class="nav panel-tabs client_transaction-sale">
                                <li><a href="#tab1" class="active" data-bs-toggle="tab">{{ __('home.payment') }}</a></li>
                    <li><a href="#tab2" data-bs-toggle="tab" class="text-dark">{{ __('home.trashed') }}</a></li>
                    </ul>
                </div>
            </div> --}}
            <div class="panel-body tabs-menu-body border-0 pt-0">
                <div class="tab-content">
                    <div class="tab-pane active" id="tab1">
                        <div class="table-responsive">
                            <div class="card mb-4 border-0 shadow-sm">
                                <div class="card-body py-3">
                                    <div class="d-flex flex-wrap align-items-center justify-content-between">
                                        <div class="d-flex align-items-center mb-2 mb-md-0">
                                            <div class="me-3">
                                                <span class="badge bg-primary p-3 fs-5">
                                                    <i class="text-white fe fe-user"></i>
                                                </span>
                                            </div>
                                            <div>
                                                <div class="text-muted fe fe-user">{{ __('home.client') }}</div>
                                                <div class="fs-5">{{ $cargo->client->name ?? $cargo->client_name }}</div>
                                            </div>
                                        </div>

                                        <div class="text-center mx-3">
                                            <div class="text-muted small">{{ __('home.total') }}</div>
                                            <div class="fs-5 text-dark">
                                                <i class="fe fe-dollar-sign text-primary"></i>
                                                {{ number_format($cargo->total) }}
                                            </div>
                                        </div>

                                        <div class="text-center mx-3">
                                            <div class="text-muted small">{{ __('home.paid') }}</div>
                                            <div class="fs-5 text-success">
                                                <i class="fe fe-check-circle"></i>
                                                {{ number_format($cargo->paid) }}
                                            </div>
                                        </div>

                                        <div class="text-center mx-3">
                                            <div class="text-muted small">{{ __('home.balance') }}</div>
                                            <div class="fs-5 text-danger">
                                                <i class="fe fe-alert-circle"></i>
                                                {{ number_format($cargo->balance) }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <table id="file-datatable" class="table table-bordered table-striped text-nowrap mb-0 table-hover">
                                <thead class="border-top">
                                    <tr>
                                        <th>{{ __('home.sn') }}</th>
                                        <th>{{ __('home.date') }}</th>
                                        <th>{{ __('home.amount') }}</th>
                                        <th>{{ __('home.description') }}</th>
                                        <th>{{ __('home.action') }}</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach($cargo->payments as $index => $payment)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $settings->date_type == 'shamsi' ? $payment->shamsi_date : $payment->miladi_date }}</td>
                                        <td>{{ number_format($payment->amount) }}</td>
                                        <td>{{ $payment->description }}</td>

                                        <td>

                                        <button class="btn-save btn btn-sm btn-outline-success" style="display:none;">{{ __('home.update') }}</button>
                                            @can('payment.edit')
                                            <button class="btn-edit btn btn-sm btn-outline-primary">{{ __('home.edit') }}</button>
                                            @endcan

                                            @can('payment.delete')
                                            <form action="{{ route('cargo_payment.destroy', $payment) }}" method="POST" style="display:inline-block" onsubmit="return confirm('Are you sure?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm p-1" style="background: transparent;">
                                                    <i class="fe fe-trash-2 text-danger fs-16"></i>
                                                </button>
                                            </form>
                                            @endcan
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

@section('pagescript')
<script>
     $(document).ready(function() {

var table = $('#file-datatable').DataTable();

// Handle Edit button click
$('#file-datatable tbody').on('click', '.btn-edit', function() {
    // Remove contenteditable attribute from all cells
    $('td[contenteditable="true"]').removeAttr('contenteditable');

    // Add contenteditable attribute to the cells in the clicked row
    var row = $(this).closest('tr');
    row.find('td:eq(2), td:eq(3)').attr('contenteditable', 'true');

    // Show the Save button for the clicked row
    row.find('.btn-save').show();

    // Hide the Edit button for the clicked row
    row.find('.btn-edit').hide();
});

// Handle Save button click
$('#file-datatable tbody').on('click', '.btn-save', function() {
    var row = $(this).closest('tr');
    var amount = row.find('td:eq(2)').text().trim();
    var description = row.find('td:eq(3)').text().trim();
    var id = row.find('td:eq(0)').text().trim();

    // Ensure that both amount and description have values before sending the request
    if (amount !== '' && description !== '') {
        sendDataToServer(row, amount, description, id);
    } else {
        alert('Please enter both amount and description before saving.');
    }

    // Remove contenteditable attribute from all cells
    $('td[contenteditable="true"]').removeAttr('contenteditable');

    // Hide the Save button for all rows
    $('.btn-save').hide();

    // Show the Edit button for all rows
    $('.btn-edit').show();
});
function sendDataToServer(row, amount, description, id) {
    // AJAX request to submit data to the Laravel controller
    var url = "{{ url('/cargo-payment/update') }}";
    var _token = "{{ csrf_token() }}";

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
        _token: _token,
        url: url,
        type: 'POST',
        data: {
            amount: amount,
            description: description,
            id: id,
            // Add more fields as needed
        },
        success: function(response) {
            // console.log(response);
            $('.success-message').html(response[1]).fadeIn().delay(3000).fadeOut();
        },
        error: function(error) {
            // console.error(error);
            $('.error-message').html('An error occurred. Please try again.').fadeIn().delay(3000).fadeOut();
        }
    });
}

});
</script>
@endsection



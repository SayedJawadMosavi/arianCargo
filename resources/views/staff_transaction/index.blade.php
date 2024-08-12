@extends('layouts.app')
@section('title', 'All Transactions')

@section('content')

<div class="card mt-4">
    @if (session()->has('success') || session()->has('error') )
    @include('layouts.partials.components.alert')
    @endif

    <div class="card-header">
        <h3 class="card-title">{{ __('home.staffs') }}</h3>

        <a href="{{ url('staff_transaction', ['id' => $ids]) }}" class="btn btn-outline-primary mx-5">{{ __('home.new_transaction') }}</a>
    </div>

    <div class="card-body pt-4">
        <div class="grid-margin">
            <div class="">
                <div class="panel panel-primary">
                    <div class="tab-menu-heading tab-menu-heading-boxed">
                        <div class="tabs-menu-boxed">
                            <!-- Tabs -->
                            <ul class="nav panel-tabs staff_transaction-sale">
                                <li><a href="#tab1" class="active" data-bs-toggle="tab">{{ __('home.all_transaction') }}</a></li>
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
                                                <th>{{ __('home.description') }}</th>
                                                <th>{{ __('home.account') }}</th>
                                                <th>{{ __('home.staff') }} </th>
                                                <th>{{ __('home.type') }}</th>

                                                <th>{{ __('home.amount') }}</th>
                                                <th>{{ __('home.date') }}</th>

                                                <th>{{ __('home.action') }}</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            @php $c =1; @endphp
                                            @foreach($logs as $staff_transaction)
                                            <tr>

                                                <td>{{$staff_transaction->id}}</td>
                                                <td>{{$staff_transaction->description}}</td>
                                                <td>{{$staff_transaction->account->name}} {{$staff_transaction->account->amount}} -{{$staff_transaction->account->currency->name}}</td>
                                                <td>{{$staff_transaction->staff->name}}</td>
                                                <td>
                                                    @if($staff_transaction->type == 'withdraw')
                                                    {{ __('home.withdraw') }}
                                                    @else
                                                    {{ __('home.deposit') }}
                                                    @endif
                                                </td>


                                                <td>{{number_format($staff_transaction->amount)}}</td>
                                                <td>
                                                    @if ($settings->date_type=='shamsi')
                                                    {{$staff_transaction->shamsi_date}}
                                                    @else
                                                    {{$staff_transaction->miladi_date}}
                                                    @endif
                                                </td>

                                                <td class="d-flex">
                                                    @if($staff_transaction->action == 'payment')
                                                    <div class="">
                                                        <a class="btn text-primary btn-sm" href="{{route('staff_transaction.edit', $staff_transaction->id)}}" data-bs-toggle="tooltip" data-bs-original-title="{{ __('home.edit') }}"><span class="fe fe-edit fs-16"></span></a>

                                                        <button type="button" class="btn text-danger btn-sm" data-bs-toggle="modal" data-bs-target="#confirmationModal{{ $staff_transaction->id }}">
                                                            <span class="fe fe-trash-2 fs-16"></span>
                                                        </button>

                                                        <!-- Confirmation Modal -->


                                                    </div>
                                                    @endif
                                                </td>
                                            </tr>
                                            <div class="modal fade " id="confirmationModal{{ $staff_transaction->id }}">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h6 class="modal-title">Confirmation</h6><button aria-label="Close" class="btn-close" data-bs-dismiss="modal"><span aria-hidden="true">&times;</span></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            Are you sure you want to delete this record?
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                            <form action="{{route('staff_transaction.destroy', $staff_transaction->id)}}" method="POST" class="d-inline">
                                                                @method('delete')
                                                                @csrf
                                                                <button type="submit" class="btn btn-danger">Delete</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            @endforeach
                                        </tbody>
                                        <tfoot class="">
                                            <tr>
                                                <th colspan="5"> {{__('home.total')}}</th>

                                                <th colspan="3" class="@if($staff_loan->loan > 0) {{'up'}} @else {{'down'}} @endif">{{number_format($staff_loan->loan)}}</th>

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
@section('pagescript')
<script>
    $('#dates').persianDatepicker({
        // minDate: new persianDate().subtract('day', 1).valueOf(),
        maxDate: new persianDate(),
        format: 'YYYY-MM-DD',
        autoClose: true,
        initialValue: true,
        initialValueType: 'persian',
        calendar: {
            persian: {
                locale: 'en'
            }
        }
    });
</script>
@endsection

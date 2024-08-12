@extends('layouts.app')
@section('title', 'All Salaries')

@section('content')

<div class="card mt-4">
    @if (session()->has('success') || session()->has('error') )
    @include('layouts.partials.components.alert')
    @endif

    <div class="card-header">
        <h3 class="card-title">{{ __('home.salaries') }}</h3>

        <a href="{{ route('staff_salary.create') }}" class="btn btn-outline-primary mx-5">{{ __('home.new_staff_salary') }}</a>
    </div>

    <div class="card-body pt-4">
        <div class="grid-margin">
            <div class="">
                <div class="panel panel-primary">
                    <div class="tab-menu-heading tab-menu-heading-boxed">
                        <div class="tabs-menu-boxed">
                            <!-- Tabs -->
                            <ul class="nav panel-tabs staff_salary-sale">
                                <li><a href="#tab1" class="active" data-bs-toggle="tab">{{ __('home.all_staff_salary') }}</a></li>
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
                                                <th>{{ __('home.no') }}</th>
                                                <th>{{ __('home.description') }}</th>
                                                <th>{{ __('home.account') }}</th>
                                                <th>{{ __('home.staff') }} </th>
                                                <th>{{ __('home.salary') }} </th>
                                                <th>{{ __('home.payable') }} </th>
                                                <th>{{ __('home.paid') }} </th>

                                                <th>{{ __('home.deduction') }} </th>

                                                <th>{{ __('home.date') }}</th>

                                                <th>{{ __('home.action') }}</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                         @php $c =1; @endphp
                                            @foreach($staff_salaries as $staff_salary)
                                            <tr>

                                                <td>{{$staff_salary->id}}</td>
                                                <td>{{$staff_salary->description}}</td>
                                                <td>{{$staff_salary->account->name}} {{$staff_salary->account->amount}} -{{$staff_salary->account->currency->name}}</td>
                                                <td>{{$staff_salary->staff->name}}</td>



                                                <td>{{number_format($staff_salary->salary)}}</td>
                                                <td>{{number_format($staff_salary->payable)}}</td>
                                                <td>{{number_format($staff_salary->paid)}}</td>

                                                <td>{{number_format($staff_salary->deduction)}}</td>
                                                <td>
                                                    @if ($settings->date_type=='shamsi')
                                                    {{$staff_salary->shamsi_date}}
                                                    @else
                                                    {{$staff_salary->miladi_date}}
                                                    @endif
                                                </td>

                                                <td class="d-flex">
                                                    <div class="">
                                                        <a class="btn text-primary btn-sm" href="{{route('staff_salary.edit', $staff_salary->id)}}" data-bs-toggle="tooltip" data-bs-original-title="{{ __('home.edit') }}"><span class="fe fe-edit fs-16"></span></a>

                                                        <button type="button" class="btn text-danger btn-sm" data-bs-toggle="modal" data-bs-target="#confirmationModal{{$staff_salary->id }}">
                                                            <span class="fe fe-trash-2 fs-16"></span>
                                                        </button>

                                                        <!-- Confirmation Modal -->
                                                        <div class="modal fade " id="confirmationModal{{ $staff_salary->id }}">
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
                                                                        <form action="{{route('staff_salary.destroy', $staff_salary)}}" method="POST" class="d-inline">
                                                                            @method('delete')
                                                                            @csrf
                                                                            <button type="submit" class="btn btn-danger">Delete</button>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>
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

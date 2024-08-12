@extends('layouts.app')
@section('title', 'All Expenses')

@section('content')

<div class="card mt-4">
    @if (session()->has('success') || session()->has('error') )
        @include('layouts.partials.components.alert')
    @endif
    <div class="card-header d-flex justify-content-between">
        <h3 class="card-title">{{ __('home.expenses') }}</h3>
        @can('expense.create')
        <a href="{{ route('expense.create') }}" class="btn btn-primary mx-5">{{ __('home.new_expense') }}</a>
        @endcan
    </div>

    <div class="card-body pt-4">
    <div class="col-sm-12 my-3">
            @php $data = 'hey'; @endphp
            <form class="" action="{{ route('expense.filter') }}" method="POST">
                <x-date-component :data="$data" />
            </form>

        </div>
        <div class="grid-margin">
            <div class="">
                <div class="panel panel-primary">
                    <div class="tab-menu-heading tab-menu-heading-boxed">
                        <div class="tabs-menu-boxed">
                            <!-- Tabs -->
                            <ul class="nav panel-tabs expense-sale">
                                <li><a href="#tab1" class="active" data-bs-toggle="tab">{{  __('home.all_expenses') }}</a></li>
                                <li><a href="#tab2" data-bs-toggle="tab" class="text-dark">{{ __('home.trashed') }}</a></li>
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
                                                <th>{{ __('home.type') }}</th>
                                                <th>{{ __('home.category') }}</th>
                                                <th>{{ __('home.amount') }}</th>
                                                <th>{{ __('home.main_currency') }}</th>
                                                <th>{{ __('home.date') }}</th>

                                                <th>{{ __('home.action') }}</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            @foreach($expenses as $expense)
                                            <tr>

                                                <td>{{$expense->id}}</td>
                                                <td>{{$expense->description}}</td>
                                                <td>@if($expense->type == 'income')
                                                    <span class="tag tag-radius tag-round tag-primary">{{__('home.income') }}</span>
                                                    @else
                                                    <span class="tag tag-radius tag-round tag-danger">{{__('home.expense') }}</span>

                                                    @endif
                                                </td>
                                                <td>{{$expense->category->name}}</td>

                                                <td>{{number_format($expense->amount, 2)}} ({{ $expense->account->currency->name }})</td>
                                                <td>{{number_format($expense->main_amount, 2)}}</td>
                                                <td>
                                                    @if ($settings->date_type=='shamsi')
                                                    {{$expense->shamsi_date}}
                                                    @else
                                                    {{$expense->miladi_date}}
                                                    @endif
                                                </td>

                                                <td class="d-flex">
                                                    <div class="">
                                                        @can('expense.edit')
                                                        <a class="btn text-primary btn-sm" href="{{route('expense.edit', $expense)}}" data-bs-toggle="tooltip" data-bs-original-title="{{ __('home.edit') }}"><span class="fe fe-edit fs-16"></span></a>
                                                        @endcan
                                                        @can('expense.delete')
                                                        <form action="{{route('expense.destroy', $expense)}}" method="POST" class="d-inline">
                                                            @method('delete')
                                                            @csrf
                                                            <button type="submit" data-bs-toggle="tooltip"  data-bs-original-title="{{ __('home.delete') }}" class="btn text-danger btn-sm"><span class="fe fe-trash-2 fs-16"></span></button>
                                                        </form>
                                                        @endcan
                                                    </div>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane" id="tab2">
                                <div class="table-responsive">
                                    <table id="file-datatable" class="table table-bordered table-striped text-nowrap mb-0 table-hover">
                                        <thead class="border-top">
                                            <tr>
                                            <th>{{ __('home.no') }}</th>
                                                <th>{{ __('home.name') }}</th>
                                                <th>{{ __('home.type') }}</th>
                                                <th>{{ __('home.category') }}</th>
                                                <th>{{ __('home.amount') }}</th>
                                                <th>{{ __('home.date') }}</th>

                                                <th>{{ __('home.action') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($trashed as $expense)
                                            <tr class="border-bottom">
                                            <td>{{$expense->id}}</td>
                                                <td>{{$expense->description}}</td>
                                                <td>{{$expense->type}}</td>
                                                <td>{{$expense->category->name}}</td>

                                                <td>{{number_format($expense->amount)}}</td>
                                                <td>
                                                    @if ($settings->date_type=='shamsi')
                                                    {{$expense->shamsi_date}}
                                                    @else
                                                    {{$expense->miladi_date}}
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="g-2 ">

                                                        <form action="{{route('expense.restore', $expense->id)}}" method="POST" class="d-inline">
                                                            @method('POST')
                                                            @csrf
                                                            <button type="submit" data-bs-toggle="tooltip"  data-bs-original-title="Restore" class="btn text-primary btn-sm"><span class="fe fe-repeat fs-14"></span></button>
                                                        </form>
                                                        <form action="{{route('expense.forceDelete', $expense->id)}}" method="POST" class="d-inline">
                                                            @method('delete')
                                                            @csrf
                                                            <button type="submit" data-bs-toggle="tooltip"  data-bs-original-title="Delete Permanently" class="btn text-danger btn-sm"><span class="fe fe-trash-2 fs-14"></span></button>
                                                        </form>
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

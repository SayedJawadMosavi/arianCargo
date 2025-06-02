@extends('layouts.app')
@section('title', 'All accounts')

@section('content')

<div class="card mt-4">
    {{-- @dd(session()->has('success')) --}}
    @if (session()->has('success') || session()->has('error') )
    @include('layouts.partials.components.alert')
    @endif
    <div class="card-header d-flex justify-content-between align-items-center bg-light border-bottom">
        <h3 class="card-title text-primary fw-bold">{{ __('home.accounts') }}</h3>
        @can('account.create')
        <a href="{{ route('account.create') }}" class="btn btn-outline-primary px-4">
            <i class="fe fe-plus me-1"></i> {{ __('home.new_account') }}
        </a>
        @endcan
    </div>

    <div class="card-body pt-4">

        {{-- Currency Summary --}}
  <div class="mb-4">
    <h5 class="text-dark fw-bold mb-3 border-bottom pb-2">
        {{ __('home.total') }}
    </h5>

    <div class="row g-3">
        @foreach($sumsByCurrency as $key => $value)
        <div class="col-sm-6 col-md-4 col-lg-3">
            <div class="bg-light border border-primary rounded-3 p-3 shadow-sm h-100">
                <div class="d-flex flex-column">
                    <span class="text-muted small text-uppercase">{{ __('home.currency') }}</span>
                    <span class="fw-semibold text-primary fs-6">{{ $key }}</span>

                    <span class="text-muted small text-uppercase mt-2">{{ __('home.total') }}</span>
                    <span class="fw-bold fs-5 text-dark">{{ number_format($value, 2) }}</span>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>


        {{-- Accounts Table --}}
        <div class="table-responsive">
            <table id="file-datatable" class="table table-bordered table-striped table-hover text-nowrap mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th>{{ __('home.sn') }}</th>
                        <th>{{ __('home.name') }}</th>
                        <th>{{ __('home.amount') }}</th>
                        @if ($branch->is_main_branch==0)

                        <th>{{ __('home.branch_payable') }}</th>
                        <th>{{ __('home.paid') }}</th>
                        <th>{{ __('home.balance') }}</th>
                        @endif
                        <th>{{ __('home.currency') }}</th>
                        <th>{{ __('home.branch') }}</th>
                        <th>{{ __('home.default') }}</th>
                        <th>{{ __('home.active') }}</th>
                        <th>{{ __('home.action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($accounts as $obj)
                    <tr>
                        <td class="text-muted">{{$obj->id}}</td>
                        <td class="fw-semibold">{{$obj->name}}</td>
                        <td>{{ number_format($obj->amount, 2) }}</td>
                        @if ($branch->is_main_branch==0)

                        <td><span class="badge bg-info">{{ number_format($obj->cargo_amount, 2) }} </span></td>
                        <td><span class="badge bg-success">{{ number_format($obj->paid_amount, 2) }} </span></td>
                        <td><span class="badge bg-danger"> {{ number_format($obj->cargo_amount - $obj->paid_amount) }} </span></td>
                        @endif
                        <td><span class="badge bg-secondary">{{ $obj->currency->name }}</span></td>
                        <td><span class="badge bg-secondary">{{ $obj->branchs->name  ??'' }}</span></td>
                        <td>
                            @if($obj->default == 1)
                            <span class="badge bg-success">{{ __('home.yes') }}</span>
                            @else
                            <span class="badge bg-danger">{{ __('home.no') }}</span>
                            @endif
                        </td>
                        <td>
                            <form action="{{ route('account.status', $obj->id) }}" method="POST" class="update_{{ $obj->id }} d-inline">
                                @method('post')
                                @csrf
                                <label class="form-check form-switch">
                                    <input type="checkbox" name="active" class="form-check-input" onchange="update({{ $obj->id }})"
                                        @if($obj->active == 1) checked @endif>
                                    <span class="form-check-label">{{ __('home.active') }}</span>
                                </label>
                            </form>
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                @can('account.edit')
                                <a class="btn btn-sm btn-outline-primary" href="{{ route('account.edit', $obj) }}" data-bs-toggle="tooltip" title="Edit">
                                    <i class="fe fe-edit"></i>
                                </a>
                                @endcan
                                <a class="btn btn-sm btn-outline-success" href="{{ route('account.statement', $obj) }}" data-bs-toggle="tooltip" title="Statement">
                                    <i class="fe fe-menu"></i>
                                </a>

                                 <a class="btn btn-outline-secondary btn-sm rounded-0" href="{{ route('account.show', $obj->id) }}" data-bs-toggle="tooltip" data-bs-original-title="{{ __('home.payment') }}">
                                                        <i class="fe fe-credit-card fs-16"></i>
                                                    </a>
                            </div>
                        </td>
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

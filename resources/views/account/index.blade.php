@extends('layouts.app')
@section('title', 'All accounts')

@section('content')

<div class="card mt-4">
    {{-- @dd(session()->has('success')) --}}
    @if (session()->has('success') || session()->has('error') )
        @include('layouts.partials.components.alert')
    @endif

    <div class="card-header d-flex justify-content-between">
        <h3 class="card-title">{{ __('home.accounts') }}</h3>
        @can('account.create')
            <a href="{{ route('account.create') }}" class="btn btn-primary mx-5">{{ __('home.new_account') }}</a>
        @endcan
    </div>

    <div class="card-body pt-4">

        <div class="table-responsive">
            <table class="table table-bordered">
                @foreach($sumsByCurrency as $key => $value)
                    <tr>
                        <th>{{ $key }}</th>
                        <td>{{ number_format($value, 2) }}</td>
                    </tr>
                @endforeach
            </table>
            <table id="file-datatable" class="table table-bordered table-striped text-nowrap mb-0 table-hover">
                <thead>
                    <tr>
                        <th>{{ __('home.sn') }}</th>
                        <th>{{ __('home.name') }}</th>
                        <th>{{ __('home.amount') }}</th>
                        <th>{{ __('home.currency') }}</th>
                        <th>{{ __('home.default') }}</th>
                        <th>{{ __('home.active') }}</th>
                        <th>{{ __('home.action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($accounts as $obj)
                    <tr>
                        <td>{{$obj->id}}</td>
                        <td>{{$obj->name}}</td>
                        <td>{{number_format($obj->amount, 2)}}</td>
                        <td>{{$obj->currency->name}}</td>
                        <td>
                            @if($obj->default ==1 )
                            <span class="tag tag-radius tag-round tag-primary">{{__('home.yes') }}</span>
                            @else
                            <span class="tag tag-radius tag-round tag-red">{{__('home.no') }}</span>
                            @endif
                        </td>
                        <td>
                            <div class="form-group">
                                <form action="{{route('account.status', $obj->id )}}" class="update_{{ $obj->id }}" method="POST" class="d-inline">
                                    @method('post')
                                    @csrf
                                    <label class="custom-switch form-switch mb-0">
                                        <input type="checkbox" name="active" class="custom-switch-input" onchange="update({{ $obj->id }})" @if(isset($obj)) @if($obj->active == 1) {{'checked'}} @endif @else {{ 'checked' }}@endif>
                                        <span class="custom-switch-indicator custom-switch-indicator-md"></span>
                                        <span class="custom-switch-description">{{ __('home.active') }}</span>
                                    </label>
                                </form>

                            </div></td>
                        <td>
                            @can('account.edit')
                            <a class="btn text-primary btn-sm" href="{{route('account.edit', $obj)}}" data-bs-toggle="tooltip" data-bs-original-title="Edit"><span class="fe fe-edit fs-14"></span></a>
                            @endcan
                            <a class="btn text-success btn-sm" href="{{route('account.statement', $obj)}}" data-bs-toggle="tooltip" data-bs-original-title="Statement"><span class="fe fe-menu fs-14"></span></a>

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
    function update($id){
        // alert('hi');
        $('.update_'+$id).submit();
    }

</script>

@extends('layouts.app')
@section('title', 'Main Stock Report')

@section('content')

<div class="card mt-4">
    {{-- @dd(session()->has('success')) --}}
    @if (session()->has('success') || session()->has('error') )
    @include('layouts.partials.components.alert')
    @endif

    <div class="card-header ">
        <h3 class="card-title">{{ __('home.main_stock_report') }}</h3>
    </div>
    <div class="card-body pt-4">
        <div class="col-sm-12 my-3">
            <form action="{{ route('report.main_stock.post') }}" method="POST">
                {{-- <x-date-component :data="$categories"/> --}}
                @csrf
                @method('POST')
                <div class="form-row align-items-center my-5 offset-md-1">
                    <div class="col-xl-3">
                        <label for="validationServer04">{{ __('home.category') }}</label>
                        <select class="form-selects form-control" id="validationServer04" aria-describedby="validationServer04Feedback" required name="category_id">
                            <option value="all">{{ __('home.all') }}</option>
                            @foreach($categories as $obj)
                            <option value="{{$obj->id}}"> {{ $obj->name }}</option>
                            @endforeach
                        </select>

                    </div>


                    <div class="col-6 col-sm-2 ">
                        <label class="" for="inlineFormInputGroup"> </label>
                        <button type="submit" class="btn btn btn-outline-primary" style="margin-top: 29px"> {{__('home.send')}}</button>
                    </div>
                </div>

            </form>

        </div>
        <div class="table-responsive">
            <table id="file-datatable" class="table table-bordered table-striped text-nowrap mb-0 table-hover">
                <thead>
                    <th>{{ __('home.sn') }}</th>
                    <th>{{ __('home.category') }}</th>
                    <th>{{ __('home.name') }}</th>
                    <th>{{ __('home.unit') }}</th>
                    <th>{{ __('home.dimensions') }}</th>
                    <th>{{ __('home.qty') }}</th>
                    <th>{{ __('home.cost') }}</th>
                    <th>{{ __('home.total') }}</th>

                    </tr>
                </thead>
                @php $gquantity =0; $gtotal = 0; @endphp
                <tbody>
                    @isset($logs)
                    {{-- @dd($logs) --}}

                    @php $gtotal=0; @endphp
                    @forelse($logs as $obj)
                    @php $total=0; @endphp
                    <tr>
                        <td>{{$loop->iteration}}</td>
                        <td>{{$obj->product->category->name}}</td>
                        <td>{{$obj->product->name}}</td>
                        <td>{{$obj->product->unit->name}}</td>
                        <td>{{$obj->product->height}} * {{$obj->product->width}} * {{$obj->product->length}}</td>
                        <td>{{$obj->available}}</td>
                        <td>{{$obj->income_price}}</td>
                        <td>{{$total = $obj->income_price * $obj->available}}</td>
                    </tr>
                    @php
                    $gquantity += $obj->available;
                    $gtotal += $total;

                    @endphp
                    @empty
                    @endforelse
                    @endisset
                </tbody>

                <tfoot>
                    <tr>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th>{{ __('home.total') }}</th>
                        <th>{{ number_format($gquantity) }}</th>
                        <th></th>
                        <th>{{ number_format($gtotal) }}</th>

                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    {{-- card-body --}}
</div>
@endsection

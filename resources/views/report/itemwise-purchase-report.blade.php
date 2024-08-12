@extends('layouts.app')
@section('title', 'Item Wise Purchase Report')

@section('content')

<div class="card mt-4">
    {{-- @dd(session()->has('success')) --}}
    @if (session()->has('success') || session()->has('error') )
        @include('layouts.partials.components.alert')
    @endif

    <div class="card-header ">
        <h3 class="card-title">{{ __('home.item_wise_purchase_report') }}</h3>
    </div>
    <div class="card-body pt-4">
        <div class="col-sm-12 my-3">
            <form action="{{ route('report.item_wise_purchase_report.post') }}" method="POST">
                {{-- <x-date-component :data="$categories"/> --}}
                @csrf
                @method('POST')
                <div class="form-row align-items-center my-5 offset-md-1">

                    {{-- @if ($settings->date_type=='shamsi')
                        <div class="col-xl-3">
                            <label for="validationServer01">{{ __('home.from_date') }}</label>
                            <input type="text" class="form-control form-control " name="from_shamsi" autocomplete="off" id="dates" >

                            @error('date')
                            <div id="" class="invalid-feedback">{{$message}}</div>
                            @enderror
                        </div>
                        <div class="col-xl-3 ">
                            <label for="validationServer01">{{ __('home.to_date') }}</label>
                            <input type="text" class="form-control form-control " name="to_shamsi" autocomplete="off" id="dates1" >

                            @error('date')
                            <div id="" class="invalid-feedback">{{$message}}</div>
                            @enderror
                        </div>
                        @else
                        <div class="col-xl-3 ">
                            <label for="validationServer01">{{ __('home.from_date') }}</label>
                            <input type="date" class="form-control " id="date" name="from_miladi" autocomplete="off" value="{{ date('Y-m-d') }}">
                            @error('date')
                            <div id="" class="invalid-feedback">{{$message}}</div>
                            @enderror
                        </div>
                        <div class="col-xl-3 ">
                            <label for="validationServer01">{{ __('home.to_date') }}</label>
                            <input type="date" class="form-control " id="date" name="to_miladi" autocomplete="off" value="{{ date('Y-m-d') }}">
                            @error('date')
                            <div id="" class="invalid-feedback">{{$message}}</div>
                            @enderror
                        </div>
                    @endif --}}

                    <div class="col-xl-4 mb-3">
                        <label for="validationServer04">{{ __('home.product') }}</label>
                        <select class="form-select form-control select2 @error('product') {{'is-invalid'}} @enderror" id="validationServer04" aria-describedby="validationServer04Feedback" required name="product_id">
                            @foreach($products as $product)
                            <option value="{{$product->id}}"> {{ $product->name }} - {{ $product->model }}</option>
                            @endforeach
                        </select>
                        @error('product_id')
                        <div id="" class="invalid-feedback">{{$message}}</div>
                        @enderror
                    </div>

                    <div class="col-6 col-sm-2 ">
                        <label class="" for="inlineFormInputGroup" >   </label>
                        <button type="submit" class="btn btn btn-outline-primary" style="margin-top: 29px">  {{__('home.send')}}</button>
                    </div>
                </div>

            </form>

        </div>
        <div class="table-responsive">
            <table id="file-datatable" class="table table-bordered table-striped text-nowrap mb-0 table-hover">
                <thead>
                    <tr>
                        <th>{{ __('home.date') }}</th>
                        <th>{{ __('home.bill') }}</th>
                        <th>{{ __('home.client') }}</th>
                        <th>{{ __('home.product') }}</th>
                        <th>{{ __('home.sell') }}</th>
                        <th>{{ __('home.quantity') }}</th>
                        <th>{{ __('home.total') }}</th>
                    </tr>
                </thead>
                @php $gquantity =0; $gprofit =0; $gtotal = 0; @endphp
                <tbody>
                    @isset($purchase)
                        @forelse($purchase as $obj)
                            @if($obj->quantity > 0)
                            @php $profit = 0; $total =0;
                            $profit = $obj->profit;
                            if($profit >= 0){
                                $class="text-success";
                            }else{
                                $class="text-danger";
                            }
                            @endphp
                            <tr>
                                @if ($settings->date_type=='shamsi')
                                    <td>{{$obj->purchase->shamsi_date}}</td>
                                @else
                                    <td>{{$obj->purchase->miladi_date}}</td>
                                    @endif
                                <td>{{$obj->purchase->id}}</td>
                                <td>{{$obj->purchase->vendor->company}} </td>
                                <td>{{$obj->product->name}} - {{$obj->product->model}}</td>
                                <td>{{number_format($obj->cost, 2)}} </td>
                                <td>{{$obj->quantity}}</td>
                                <td>{{number_format($total = $obj->cost * $obj->quantity, 2) }}</td>
                            </tr>
                            @endif
                            @php $gquantity += $obj->quantity;
                            $gprofit += $profit;
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
                        <th>{{ number_format($gquantity,2) }}</th>
                        <th>{{ number_format($gtotal,2) }}</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    {{-- card-body --}}
</div>
@endsection

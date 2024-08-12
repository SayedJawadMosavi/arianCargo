@extends('layouts.app')

@section('title', 'New return')
@section('content')

    <div class="card mt-4">

        @if (session()->has('success') || session()->has('error') )
            @include('layouts.partials.components.alert')
        @endif

        <div class="card-header d-flex justify-content-between">
            <h4 class="card-title">
                @if(isset($return))
                    {{ __('home.edit_return') }}
                @else
                    {{ __('home.new_return') }}
                @endif
            </h4>
            <a href="{{route('sellreturn.index')}}" class="btn btn-primary">{{ __('home.all_returns') }}</a>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
        <div class="card-body ">
            <form action="{{isset($return) ? route('sellreturn.update', $return->id) : route('sellreturn.store')}}" method="POST" enctype="multipart/form-data">
                @csrf
                @if(isset($return))
                    @method('PUT')
                @else
                    @method('POST')
                @endif

                <div class="form-row mb-3">

                    @if ($settings->date_type=='shamsi')

                        <div class="col-xl-4 mb-3">
                            <label for="validationServer01">{{ __('home.date') }}</label>
                            <input type="text" class="form-control form-control @error('date') {{'is-invalid'}} @enderror" name="shamsi_date" autocomplete="off" id="dates" value="{{isset($return) ? $return->shamsi_date : old('date')}}">
                            @error('date')
                            <div id="" class="invalid-feedback">{{$message}}</div>
                            @enderror
                        </div>
                        @else
                        <div class="col-xl-4 mb-3">
                            <label for="validationServer01">{{ __('home.date') }}</label>
                            <input type="date" class="form-control @error('date') {{'is-invalid'}} @enderror" id="date"  name="miladi_date" value="{{ isset($return) ? $return->miladi_date : date('Y-m-d') }}">
                            @error('date')
                            <div id="" class="invalid-feedback">{{$message}}</div>
                            @enderror
                        </div>
                    @endif
                    <div class="col-xl-8 mb-3">
                        <label for="validationServer01">{{ __('home.description') }}</label>
                        <input type="text" class="form-control @error('description') {{'is-invalid'}} @enderror" id="description"  name="description" value="{{isset($return) ? $return->description : old('description')}}">
                        @error('description')
                        <div id="" class="invalid-feedback">{{$message}}</div>
                        @enderror
                    </div>

                    <div class="col-xl-3 mb-3">
                        <label for="validationServer04">{{ __('home.sell') }}</label>
                        <select class="form-select form-control select2 @error('sell_id') {{'is-invalid'}} @enderror" id="sell_id" aria-describedby="validationServer04Feedback" required name="sell_id" onchange="showData(this.value);">
                            <option> {{__('home.please_select')}}</option>
                            @foreach($sells as $sell)
                                <option value="{{$sell->id}}" @if(isset($return)) @if($return->sell_id == $sell->id) selected = 'selected' @endif @endif >{{$sell->id}} - {{$sell->client->name}} - {{ isset($sell->shamsi_date) ? $sell->shamsi_date : $sell->miladi_date }}</option>
                            @endforeach
                        </select>
                        @error('sell_id')
                        <div id="" class="invalid-feedback">{{$message}}</div>
                        @enderror
                    </div>
                    <div class="col-xl-4 mb-3">
                        <label for="validationServer04">{{ __('home.product') }}</label>
                        <select class="form-select form-control  @error('product_id') {{'is-invalid'}} @enderror" required name="product_id" id="product_id">

                        </select>
                        @error('product_id')
                        <div id="" class="invalid-feedback">{{$message}}</div>
                        @enderror
                    </div>

                    <div class="col-xl-2 mb-3">
                        <label >{{ __('home.quantity') }}</label>
                        <input type="number" id="quantity" name="quantity" class="form-control" value="{{isset($return) ? $return->quantity : old('quantity',0)}}">
                        <input type="hidden" id="qty" name="qty" >
                    </div>
                    <div class="col-xl-3 mb-3">
                        <label >{{ __('home.cost') }}</label>
                        <input type="number" step="0.01" id="cost" readonly name="cost" class="form-control" value="{{isset($return) ? $return->cost : old('cost, 0')}}">
                    </div>
                </div>

                <div class="form-footer mt-2">
                    <input type="submit" class="btn btn-primary" value="@if(isset($return))
                        {{ __('home.update') }}
                    @else
                        {{ __('home.save') }}
                    @endif">
                </div>

            </form>

        </div>
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

<script>

    function showData(value) {


        $.ajax({
            url: "{{URL::asset('/')}}" + "select_data/" + value,
            method: 'GET',
            success: function(data) {
                $('#product_id').html(data.data);
            }
        });

    }
    @isset($return)
    $.ajax({
        type: 'POST',
        url: "{{URL::asset('/')}}" + "select_data/" + {!! $return->sell_id !!},
        method: 'GET',

        success: function(response) {
            $('#product_id').html(response.data);

        }
    });
    @endisset
    $('#product_id').change(function() {
      // Get the selected option
        var cost = parseFloat($(this).find(':selected').data('cost'));
        var qty = parseFloat($(this).find(':selected').data('qty'));
        // console.log(cost);
        $('#cost').val(cost);
        $('#qty').val(qty);
    });

    $('#quantity').keyup(function() {
        var qty = parseInt($('#qty').val());
        if($(this).val() > qty){
            alert('مقدار بزرگتر از فروش است.');
            $('#qty').val(0);
            $('#quantity').val(0);
            return false;
        }
    });
</script>


@endsection




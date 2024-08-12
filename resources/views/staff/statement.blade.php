@extends('layouts.app')
@section('title', 'staff Statement')

@section('content')

<div class="card mt-4">
    {{-- @dd(session()->has('success')) --}}
    @if (session()->has('success') || session()->has('error') )
        @include('layouts.partials.components.alert')
    @endif

    <div class="card mt-4">
    <div class="card-header d-flex justify-content-between">
        <h4 class="card-title">
            @if(isset($staff_transactions))
            {{ __('home.edit_transaction') }}
            @else
            {{ __('home.new_transaction') }}
            @endif
        </h4>
        @if (session()->has('success') || session()->has('error') )
        @include('layouts.partials.components.alert')
        @endif
        <a href="{{route('client_transaction.index')}}" class="btn btn-primary">{{ __('home.all_transaction') }}</a>

    </div>
    <div class="card-body ">
        <form action="{{isset($staff_transactions) ? route('client_transaction.update', $staff_transactions) : route('client_transaction.store')}}" method="POST" enctype="multipart/form-data">
            @csrf
            @if(isset($staff_transactions))
            @method('PUT')
            @else
            @method('POST')
            @endif

            <div class="form-row mb-3">

                <div class="col-xl-3 mb-3">
                    <label for="validationServer04">{{ __('home.account') }}</label>
                    <select class="form-selects form-control @error('account') {{'is-invalid'}} @enderror" id="account_id" aria-describedby="validationServer04Feedback" required name="account_id">
                    <option selected disabled value="">Choose...</option>
                    @foreach($accounts as $account)
                        <option value="{{$account->id}}" @if(isset($staff_transactions)) @if($staff_transactions->account_id == $account->id) selected = 'selected' @endif @endif> {{ $account->name }} - {{ $account->currency->name }} - {{ $account->amount }}</option>
                        @endforeach
                    </select>
                    @error('account_id')
                    <div id="" class="invalid-feedback">{{$message}}</div>
                    @enderror
                </div>
                <div class="col-xl-3 mb-3">
                    <label for="client_id">{{ __('home.client') }}</label>
                    <select class="form-selects form-control @error('client') {{'is-invalid'}} @enderror" id="client_id" aria-describedby="validationServer04Feedback" required name="client_id" onchange="showData(this.value);">
                        <option selected disabled value="">Choose...</option>
                        @foreach($clients as $client)
                        <option value="{{$client->id}}" @if(isset($staff_transactions)) @if($staff_transactions->client_id == $client->id) selected = 'selected' @endif @endif> {{ $client->name }}</option>
                        @endforeach
                    </select>
                    @error('client_id')
                    <div id="" class="invalid-feedback">{{$message}}</div>
                    @enderror
                </div>
                <div class="col-xl-3 mb-3">

                    <label class="" for="inlineFormInputGroup">{{__('home.accounts')}}</label>

                    <select class="form-control " name="client_account_id" id="client_account_id" onchange="checkCurrency(this.value);">
                    <option selected disabled value="">Choose...</option>

                </select>

                </div>
                <div class="col-12 col-sm-4 col-md-3 col-md-2">
                    <label class="" for="inlineFormInputGroup">{{__('home.type')}}</label>
                    <select class="form-control " name="type" id="type" >
                    <option selected disabled value="">Choose...</option>
                        <option value="deposit" @if(isset($staff_transactions)) @if($staff_transactions->type == 'deposit') selected = 'selected' @endif @endif >{{__('home.deposit')}}</option>
                        <option value="withdraw" @if(isset($staff_transactions)) @if($staff_transactions->type == 'withdraw') selected = 'selected' @endif @endif >{{__('home.withdraw')}}</option>
                    </select>
                    @error('type')
                    <span class="alert text-danger">{{$message}}</span>
                    @enderror
                </div>

                <div class="col-xl-3 mb-3">
                    <label for="validationServer01">{{ __('home.amount') }}</label>
                    <input type="text" class="form-control @error('amount') {{'is-invalid'}} @enderror" id="amount" name="amount" value="{{isset($staff_transactions) ? $staff_transactions->amount : old('amount')}}">
                    @error('amount')
                    <div id="" class="invalid-feedback">{{$message}}</div>
                    @enderror
                </div>
                <div class="col-xl-3 mb-3" style="display: none;" id="exchange_rates">
                    <label class="label_rate_from" for="inlineFormInputGroup">
                        {{__('home.exchange_rate')}}</label>
                    <input type="text" class="form-control " name="exchange_rate" id="exchange_rate" value="{{isset($staff_transactions) ? $staff_transactions->rate : old('rate')}}">


                </div>
                <div class="col-12 col-sm-4 col-md-3 col-md-2" style="display: none;" id="operations">
                    <label class="" for="inlineFormInputGroup">{{__('home.operation')}}</label>
                    <select class="form-control " name="operation" id="operation" onchange="exchange_data(this.value);">
                    <option selected disabled value="">Choose...</option>
                    <option value="multiply" @if(isset($staff_transactions)) @if($staff_transactions->operation == 'multiply') selected = 'selected' @endif @endif >{{__('home.multiply')}}</option>
                        <option value="divide" @if(isset($staff_transactions)) @if($staff_transactions->operation == 'divide') selected = 'selected' @endif @endif >{{__('home.divide')}}</option>
                    </select>

                </div>
                <div class="col-xl-3 mb-3" style="display: none;" id="totals">
                    <label class="" for="inlineFormInputGroup"> {{__('home.total')}}</label>
                    <input type="number" step="0.01" class="form-control " name="total" id="total" value="{{isset($staff_transactions) ? $staff_transactions->total : old('total')}}">

                </div>
                @if ($settings->date_type=='shamsi')
                <div class="col-xl-3 mb-3">
                    <label for="validationServer01">{{ __('home.date') }}</label>
                    <input type="text" class="form-control form-control @error('date') {{'is-invalid'}} @enderror" name="shamsi_date" autocomplete="off" id="dates" value="{{isset($staff_transactions) ? $staff_transactions->date : old('date')}}">

                    @error('date')
                    <div id="" class="invalid-feedback">{{$message}}</div>
                    @enderror
                </div>
                @else
                <div class="col-xl-3 mb-3">
                    <label for="validationServer01">{{ __('home.date') }}</label>
                    <input type="date" class="form-control @error('date') {{'is-invalid'}} @enderror" id="date" name="miladi_date" value="{{ isset($staff_transactions) ? $staff_transactions->miladi_date : date('Y-m-d') }}">
                    @error('date')
                    <div id="" class="invalid-feedback">{{$message}}</div>
                    @enderror
                </div>
                @endif
                <div class="col-12 col-sm-12">
                    <label class="" for="inlineFormInputGroup">{{__('home.description')}}</label>
                    <textarea name="description" class="form-control" id="description">{{isset($staff_transactions) ? $staff_transactions->description: old('description')}}</textarea>

                    @error('address')
                    <span class="alert text-danger">{{$message}}</span>
                    @enderror
                </div>
            </div>

            <div class="form-footer mt-2">
                <input type="submit" class="btn btn-primary" value="@if(isset($staff_transactions))
                    {{ __('home.update') }}
                @else
                    {{ __('home.save') }}
                @endif">
            </div>

        </form>

    </div>
</div>

    <div class="card-body pt-4">
        <table class="table table-bordered">
            @foreach ($staff->currency as $cur )
            <tr>
                <th>{{$cur->currency->name}}</th>
                <td>{{$cur->amount}}</td>
            </tr>

            @endforeach
        </table>

        <div class="table-responsive">
            <table id="data-table1" class="table table-bordered table-striped text-nowrap mb-0 table-hover">
                <thead>
                    <tr>
                        <th>{{ __('home.date') }}</th>
                        <th>{{ __('home.description') }}</th>
                        <th>{{ __('home.currency') }}</th>
                        <th>{{ __('home.amount') }}</th>
                        <th>{{ __('home.transaction_type') }}</th>

                    </tr>
                </thead>
                <tbody>
                    @foreach($logs as $obj)
                    <tr>
                        <td>{{$obj->shamsi_date}}</td>
                        <td>{{$obj->description}}</td>
                        <td>{{$obj->staff_currency->currency->name}}</td>
                        <td>{{number_format($obj->amount)}}</td>
                        <td>
                            @if($obj->type == 'deposit' )
                            <span class="tag tag-radius tag-round tag-primary">{{__('home.deposit') }}</span>
                            @else
                            <span class="tag tag-radius tag-round tag-red">{{__('home.withdraw') }}</span>
                            @endif
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

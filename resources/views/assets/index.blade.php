@extends('layouts.app')
@section('title', 'All Assets')

@section('content')

<div class="card">

    @include('layouts.partials.components.alert')
    <div class="card-header d-flex justify-content-between">
        <h3 class="card-title">{{ __('home.all_assets') }}</h3>
        @can('asset.create')
        <a href="{{route('asset.create')}}" class="btn btn-primary">{{ __('home.new_asset') }}</a>
        @endcan

    </div>

    <div class="card-body pt-4">

        <div class="table-responsive">
            <table id="file-datatable" class="table table-bordered table-striped text-nowrap mb-0 table-hover">
                <thead class="border-top">
                    <tr>
                        <th> {{ __('home.sn') }}</th>
                        <th> {{ __('home.name') }}</th>
                        <th> {{ __('home.category') }}</th>
                        <th> {{ __('home.currency') }}</th>
                        <th> {{ __('home.quantity') }}</th>
                        <th> {{ __('home.asset_value') }}</th>
                        <th> {{ __('home.description') }}</th>
                        <th> {{ __('home.action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($assets as $asset)
                    <tr class="border-bottom">
                        <td>{{$asset->id}}</td>
                        <td>{{$asset->name}}</td>
                        <td>{{$asset->assets_category->name}}</td>
                        <td>{{$asset->currency->name}}</td>
                        <td>{{$asset->quantity}}</td>
                        <td>{{$asset->assets_value}}</td>
                        <td>{{$asset->description}}</td>


                       
                        <td>
                            <div class="g-2 d-flex">
                                <a class="btn text-primary btn-sm" href="{{route('asset.edit', $asset->id)}}" data-bs-toggle="tooltip" data-bs-original-title="Edit"><span class="fe fe-edit fs-14"></span></a>
                                <form action="{{route('asset.destroy', $asset->id)}}" method="POST">
                                    @method('delete')
                                    @csrf
                                    <button type="submit" data-bs-toggle="tooltip" data-bs-original-title="Delete" class="btn text-danger btn-sm"><span class="fe fe-trash-2 fs-14"></span></button>
                                </form>
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

@extends('layouts.app')
@section('title', 'All branch')

@section('content')

<div class="card">

    @include('layouts.partials.components.alert')
    <div class="card-header d-flex justify-content-between">
        <h3 class="card-title">{{ __('home.all_branch') }}</h3>
        @can('branch.create')
        <a href="{{route('branch.create')}}" class="btn btn-primary">{{ __('home.new_branch') }}</a>
        @endcan

    </div>

    <div class="card-body pt-4">

        <div class="table-responsive">
            <table id="file-datatable" class="table table-bordered text-nowrap mb-0">
                <thead class="border-top">
                    <tr>
                        <th> {{ __('home.sn') }}</th>
                        <th> {{ __('home.name') }}</th>
                        <th> {{ __('home.contact_person') }}</th>
                        <th> {{ __('home.mobile') }}</th>
                        <th> {{ __('home.address') }}</th>


                        <th> {{ __('home.photo') }}</th>
                        <th> {{ __('home.action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($branches as $branch)
                    <tr class="border-bottom">
                        <td>{{$branch->id}}</td>
                        <td>{{$branch->name}}</td>
                        <td>{{$branch->contact_person}}</td>
                        <td>{{$branch->mobile1}}</td>
                        <td>{{$branch->address}}</td>


                        <td>
                            <img src="/{{$branch->logo}}" alt="" width="50" class="img img-circle">
                        </td>
                        <td>
                            <div class="g-2 d-flex">
                                <a class="btn text-primary btn-sm" href="{{route('branch.edit', $branch->id)}}" data-bs-toggle="tooltip" data-bs-original-title="Edit"><span class="fe fe-edit fs-14"></span></a>
                                <form action="{{route('branch.destroy', $branch)}}" method="POST">
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

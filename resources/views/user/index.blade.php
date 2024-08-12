@extends('layouts.app')
@section('title', 'All users')

@section('content')

<div class="card mt-4">

    <div class="card-header d-flex justify-content-between">
        <h3 class="card-title">{{ __('home.all_users') }}</h3>
        @can('users.create')
        <a href="{{route('users.create')}}" class="btn btn-primary">{{ __('home.new_user') }}</a>
        @endcan

        @include('layouts.partials.components.alert')
    </div>

    <div class="card-body pt-4">

        <div class="table-responsive">
            <table id="file-datatable" class="table table-bordered text-nowrap mb-0">
                <thead class="border-top">
                    <tr>
                        <th> {{ __('home.sn') }}</th>
                        <th> {{ __('home.name') }}</th>
                        <th> {{ __('home.email') }}</th>
                        <th> {{ __('home.role') }}</th>
                        <th> {{ __('home.branch') }}</th>
                        <th> {{ __('home.photo') }}</th>
                        <th> {{ __('home.action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr class="border-bottom">
                        <td>{{$user->id}}</td>
                        <td>{{$user->name}}</td>
                        <td>{{$user->email}}</td>

                        <td>
                            @if(!empty($user->getRoleNames()))
                            @foreach($user->getRoleNames() as $v)
                            <label class="btn btn-outline-success">{{ $v }}</label>
                            @endforeach
                            @endif
                        </td>
                        <td>{{$user->branch->name}}</td>
                        <td>
                            <img src="/{{$user->image}}" alt="" width="50" class="img img-circle">
                        </td>
                        <td>
                            <div class="g-2 d-flex">
                                <a class="btn text-primary btn-sm" href="{{route('users.edit', $user->id)}}" data-bs-toggle="tooltip" data-bs-original-title="Edit"><span class="fe fe-edit fs-14"></span></a>
                                @if($user->id != auth()->id())
                                <form action="{{route('users.destroy', $user)}}" method="POST">
                                    @method('delete')
                                    @csrf
                                    <button type="submit" data-bs-toggle="tooltip" data-bs-original-title="Delete" class="btn text-danger btn-sm"><span class="fe fe-trash-2 fs-14"></span></button>
                                </form>
                                @endif
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

@extends('layouts.app')
@section('title', 'All roles')

@section('content')

<div class="card mt-4">

    <div class="card-header d-flex justify-content-between">
        <h3 class="card-title">{{ __('home.all_roles') }}</h3>
        @can('roles.create')
        <a href="{{route('roles.create')}}" class="btn btn-primary">{{ __('home.new_role') }}</a>
        @endcan
        @include('layouts.partials.components.alert')

    </div>

    <div class="card-body pt-4">

        <div class="table-responsive">
            <table id="file-datatable" class="table table-bordered text-nowrap mb-0">
                <thead class="border-top">
                    <tr>
                        <th class="bg-transparent border-bottom-0" style="width: 5%;"> {{ __('home.sn') }}</th>

                        <th class="bg-transparent border-bottom-0"> {{ __('home.role') }}</th>


                        <th class="bg-transparent border-bottom-0" style="width: 10%;">{{ __('home.action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @php $c = 1 @endphp
                    @foreach ($roles as $role)
                    <tr>
                        <td scope="row">{{$c++}} </td>
                        <td>{{ $role->name }}</td>
                        <td class="d-flex">

                            <a href="{{route('roles.edit', $role)}}" title="{{__('home.btnEdit')}}" class="btn btn-success btn-sm"><i class="fa fa-edit"></i></a>&nbsp;


                            <!-- <a class="btn btn-info btn-sm" href="{{ route('roles.show', $role->id) }}"><i class="fa fa-eye"></i></a>&nbsp; -->

                            @can('roles.delete')
                            <form action="{{ route('roles.destroy',$role->id) }}" method="POST"
                                style="display:inherit !important">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" style="margin-right:6px"><i class="fa fa-trash"></i></button>
                            </form>
                            @endcan
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>

</div>
@endsection

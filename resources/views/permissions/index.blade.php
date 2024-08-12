@extends('layouts.app')
@section('title', 'All permissions')

@section('content')

<div class="card mt-4">

    <div class="card-header d-flex justify-content-between">
        <h3 class="card-title">{{ __('home.all_permissions') }}</h3>
        @can('permissions.delete')
        <a href="{{route('permissions.create')}}" class="btn btn-primary">{{ __('home.new_permission') }}</a>
        @endcan
        @include('layouts.partials.components.alert')

    </div>

    <div class="card-body pt-4">

        <div class="table-responsive">
        <table class="table table-sm table-striped" id="file-datatable">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col"> {{ __('home.name') }}</th>

                        <th scope="col">{{ __('home.action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @php $c = 1 @endphp
                    @foreach ($permissions as $permission)
                    <tr>
                        <td scope="row">{{ $loop->iteration }}</td>
                        {{-- <td>{{ $permission->display_name }}</td> --}}
                        <td>{{ __("permissions.{$permission->name}") }}</td>

                        <td class="d-flex">
                            <a href="{{route('permissions.edit', $permission)}}" title="Edit"
                                class="btn btn-success btn-sm"><i class="fa fa-edit"></i></a>&nbsp;

                            <form action="{{ route('permissions.destroy',$permission->id) }}" method="POST"
                                style="display:none !important">
                                @csrf
                                @method('DELETE')

                                <button type="submit" class="btn btn-sm btn-danger" style="margin-right:6px"><i class="fa fa-trash"></i></button>
                            </form>

                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>

</div>
@endsection

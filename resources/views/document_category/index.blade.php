@extends('layouts.app')
@section('title', 'All Categories')

@section('content')

<div class="card mt-4">
    @if (session()->has('success') || session()->has('error') )
    @include('layouts.partials.components.alert')
    @endif
    <div class="card-header">
        <h3 class="card-title">{{ __('home.categories') }}</h3>
        <a href="{{ route('document_category.create') }}" class="btn btn-outline-primary mx-5">{{ __('home.new_category') }}</a>
    </div>

    <div class="card-body pt-4">
        <div class="grid-margin">
            <div class="">
                <div class="panel panel-primary">
                    <div class="tab-menu-heading tab-menu-heading-boxed">
                        <div class="tabs-menu-boxed">
                            <!-- Tabs -->
                            <ul class="nav panel-tabs product-sale">
                                <li><a href="#tab1" class="active" data-bs-toggle="tab">{{ __('home.all_categories') }}</a></li>
                                <li><a href="#tab2" data-bs-toggle="tab" class="text-dark">{{ __('home.trashed') }}</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="panel-body tabs-menu-body border-0 pt-0">
                        <div class="tab-content">
                            <div class="tab-pane active" id="tab1">
                                <div class="table-responsive">
                                    <table id="data-table1" class="table table-bordered table-striped text-nowrap mb-0 table-hover">
                                        <thead>
                                            <tr>
                                                <th>{{ __('home.sn') }}</th>
                                                <th>{{ __('home.name') }}</th>

                                                <th>{{ __('home.active') }}</th>
                                                <th>{{ __('home.action') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($categories as $category)
                                            <tr>
                                                <td>{{$category->id}}</h6>
                                                </td>
                                                <td>{{$category->name}}</h6>
                                                </td>

                                                <td>
                                                    @if($category->status ==1 )
                                                    <span class="tag tag-radius tag-round tag-primary">{{__('home.yes') }}</span>
                                                    @else
                                                    <span class="tag tag-radius tag-round tag-red">{{__('home.no') }}</span>
                                                    @endif
                                                    </h6>
                                                </td>
                                                <td>
                                                    <a class="btn text-primary btn-sm" href="{{route('document_category.edit', $category)}}" data-bs-toggle="tooltip" data-bs-original-title="Edit"><span class="fe fe-edit fs-14"></span></a>
                                                    <form action="{{route('document_category.destroy', $category->id)}}" method="POST" class="d-inline">
                                                        @method('delete')
                                                        @csrf
                                                        <button type="submit" data-bs-toggle="tooltip" data-bs-original-title="Delete" class="btn text-danger btn-sm"><span class="fe fe-trash-2 fs-14"></span></button>
                                                    </form>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane" id="tab2">
                                <div class="table-responsive">
                                    <table id="data-table1" class="table table-bordered table-striped text-nowrap mb-0 table-hover">
                                        <thead class="border-top">
                                            <tr>
                                                <th>{{ __('home.no') }}</th>
                                                <th>{{ __('home.name') }}</th>
                                                <th>{{ __('home.active') }}</th>
                                                <th>{{ __('home.action') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($trashed as $category)
                                            <tr>
                                                <td>{{$category->id}}</h6>
                                                </td>
                                                <td>{{$category->name}}</h6>
                                                </td>
                                                <td>{{$category->status}}</h6>
                                                </td>
                                                <td>
                                                    <form action="{{route('document_category.restore', $category)}}" method="POST" class="d-inline">
                                                        @method('POST')
                                                        @csrf
                                                        <button type="submit" data-bs-toggle="tooltip" data-bs-original-title="Restore" class="btn text-primary btn-sm"><span class="fe fe-repeat fs-14"></span></button>
                                                    </form>
                                                    <form action="{{route('document_category.forceDelete', $category)}}" method="POST" class="">
                                                        @method('delete')
                                                        @csrf
                                                        <button type="submit" data-bs-toggle="tooltip" data-bs-original-title="Delete Permanently" class="btn text-danger btn-sm"><span class="fe fe-trash-2 fs-14"></span></button>
                                                    </form>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>


                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- card-body --}}
</div>
@endsection

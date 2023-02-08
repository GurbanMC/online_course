@extends('admin.layouts.app')
@section('title')
    @lang('app.customers')
@endsection
@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3 text-light">
        <div class="h4 mb-0">
            @lang('app.customers')
        </div>
    </div>
    @include('admin.customer.edit')

    <div class="table-responsive">
        <table class="table table-dark table-striped table-hover">
            <thead>
            <tr class="text-light">
                <th scope="col">ID</th>
                <th scope="col">Name</th>
                <th scope="col">Username/Phone</th>
                <th scope="col">Password</th>
                <th scope="col">Created at</th>
            </tr>
            </thead>
            <tbody>
            @foreach($objs as $obj)
                <tr>
                    <td>{{ $obj->id }}</td>
                    <td>
                        <div class="mb-1">
                            {{ $obj->name }}
                        </div>
                    </td>
                    <td>
                        <div class="mb-1">
                            {{ $obj->username }}
                        </div>
                    </td>
                    <td>
                        <div class="mb-1">
                            {{ $obj->password }}
                        </div>
                    </td>
                    <td>{{ $obj->created_at }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection

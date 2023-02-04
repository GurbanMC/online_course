@extends('admin.layouts.app')
@section('title')
    @lang('app.brands')
@endsection
@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="h4 mb-0">
            @lang('app.brands')
        </div>
        <div>
            <a href="{{ route('admin.brands.create') }}" class="btn btn-danger btn-sm">
                <i class="bi-plus-lg"></i> @lang('app.add')
            </a>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-hover table-striped">
            <thead>
            <tr>
                <th scope="col">ID</th>
                <th scope="col">Image</th>
                <th scope="col">Name</th>
                <th scope="col">In stocks</th>
                <th scope="col">Out of stocks</th>
                <th scope="col"><i class="bi-gear-fill"></i></th>
            </tr>
            </thead>
            <tbody>
            @foreach($objs as $obj)
                <tr>
                    <td>{{ $obj->id }}</td>
                    <td>
                        <img src="{{  $obj->getImage() }}" alt="{{ $obj->getName() }}" class="img-fluid rounded" style="max-height:5rem;">
                    </td>
                    <td>
                        <a href="{{ route('brand', $obj->slug) }}" class="text-decoration-none">
                            {{ $obj->name }} <i class="bi-box-arrow-up-right"></i>
                        </a>
                    </td>
                    <td>
                        <a href="{{ route('admin.products.index', ['brand' => $obj->id, 'stock' => 1]) }}" class="text-decoration-none">
                            {{ $obj->in_stock_products_count }} <i class="bi-box-arrow-up-right"></i>
                        </a>
                    </td>
                    <td>
                        <a href="{{ route('admin.products.index', ['brand' => $obj->id, 'stock' => 0]) }}" class="text-decoration-none">
                            {{ $obj->out_of_stock_products_count }} <i class="bi-box-arrow-up-right"></i>
                        </a>
                    </td>
                    <td>
                        <a href="{{ route('admin.brands.edit', $obj->id) }}" class="btn btn-success btn-sm my-1">
                            <i class="bi-pencil"></i>
                        </a>
                        <button type="button" class="btn btn-secondary btn-sm my-1" data-bs-toggle="modal" data-bs-target="#delete{{ $obj->id }}">
                            <i class="bi-trash"></i>
                        </button>
                        <div class="modal fade" id="delete{{ $obj->id }}" tabindex="-1" aria-labelledby="delete{{ $obj->id }}Label" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <div class="modal-title fs-5 fw-semibold" id="delete{{ $obj->id }}Label">
                                            {{ $obj->getName() }}
                                        </div>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-footer">
                                        <form action="{{ route('admin.brands.destroy', $obj->id) }}" method="post">
                                            @method('DELETE')
                                            @csrf
                                            @honeypot
                                            <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">@lang('app.close')</button>
                                            <button type="submit" class="btn btn-secondary btn-sm"><i class="bi-trash"></i> @lang('app.delete')</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
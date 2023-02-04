@extends('admin.layouts.app')
@section('title')
    @lang('app.orders')
@endsection
@section('content')
    <div class="h4 mb-3">
        <a href="{{ route('admin.orders.index') }}" class="text-decoration-none">
            @lang('app.orders')
        </a>
        <i class="bi-chevron-right small"></i>
        @lang('app.edit')
    </div>

    <div class="row mb-3">
        <div class="col-10 col-sm-8 col-md-6 col-lg-4">
            <form action="{{ route('admin.orders.update', $obj->id) }}" method="post">
                @method('PUT')
                @csrf
                @honeypot

                <div class="mb-3">
                    <label for="productCode" class="form-label fw-semibold">
                        @lang('app.productCode')
                    </label>
                    <input type="text" name="productCode" id="productCode" class="form-control" autofocus>
                </div>

                <div class="mb-3">
                    <label for="quantity" class="form-label fw-semibold">
                        @lang('app.quantity')
                    </label>
                    <input type="text" name="quantity" id="quantity" class="form-control">
                </div>

                <div class="mb-3">
                    <label for="status" class="form-label fw-semibold">
                        @lang('app.status')
                        <span class="text-danger">*</span>
                    </label>
                    <select class="form-select" name="status" id="status" required>
                        @foreach( $statuses as $status )
                            @if( $status['id'] >= $obj->status )
                                <option value="{{ $status['id'] }}" {{ $status['id'] === $obj->status ? 'selected' : '' }}>@lang('app.' . $status['name'])</option>
                            @endif
                        @endforeach
                    </select>
                </div>

                <div class="row justify-content-lg-between align-items-end">
                    <div class="col-auto">
                        <button type="submit" class="btn btn-primary">
                            @lang('app.update')
                        </button>
                    </div>
                    <div class="col-auto ">
                        <a href="{{ route('admin.orders.show', $obj->id) }}" class="bi-back"> @lang('app.back')</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
@extends('admin.layouts.app')
@section('title')
    @lang('app.orderProducts')
@endsection
@section('content')
    <div class="h4 mb-3">
        <a href="{{ route('admin.orders.show', $obj->id) }}" class="text-decoration-none">
            @lang('app.orderProducts')
        </a>
        <i class="bi-chevron-right small"></i>
        @lang('app.edit')
    </div>

    <div class="row mb-3">
        <div class="col-10 col-sm-8 col-md-6 col-lg-4">
            <form action="{{ route('admin.orderProducts.update', $obj->id) }}" method="post">
                @method('PUT')
                @csrf
                @honeypot

                <div class="mb-3">
                    <label for="quantity" class="form-label fw-semibold">
                        @lang('app.quantity')
                        <span class="text-danger">*</span>
                    </label>
                    <input type="number" min="1" class="form-control @error('quantity') is-invalid @enderror" name="quantity" id="quantity" value="{{ $obj->quantity }}" required autofocus>
                    @error('quantity')
                    <div class="alert alert-danger mt-2">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="discount_percent" class="form-label fw-semibold">
                        @lang('app.discountPercent')
                        <span class="text-danger">*</span>
                    </label>
                    <input type="number" min="0" max="100" class="form-control @error('discount_percent') is-invalid @enderror" name="discount_percent" id="discount_percent" value="{{ $obj->discount_percent }}" required>
                    @error('discount_percent')
                    <div class="alert alert-danger mt-2">{{ $message }}</div>
                    @enderror
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
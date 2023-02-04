@extends('admin.layouts.app')
@section('title')
    @lang('app.brands')
@endsection
@section('content')
    <div class="h4 mb-3">
        <a href="{{ route('admin.brands.index') }}" class="text-decoration-none">
            @lang('app.brands')
        </a>
        <i class="bi-chevron-right small"></i>
        @lang('app.edit')
    </div>

    <div class="row mb-3">
        <div class="col-10 col-sm-8 col-md-6 col-lg-4">
            <form action="{{ route('admin.brands.update', $obj->id) }}" method="post">
                @method('PUT')
                @csrf
                @honeypot

                <div class="mb-3">
                    <label for="name" class="form-label fw-semibold">
                        @lang('app.name')
                        <span class="text-danger">*</span>
                    </label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" id="name" value="{{ $obj->name }}" required autofocus>
                    @error('name')
                    <div class="alert alert-danger mt-2">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="image" class="form-label fw-semibold">
                        @lang('app.image')
                    </label>
                    <input type="file" accept="image/jpeg" class="form-control @error('image') is-invalid @enderror" name="image" id="image">
                    @error('image')
                    <div class="alert alert-danger mt-2">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">
                    @lang('app.update')
                </button>
            </form>
        </div>
    </div>
@endsection
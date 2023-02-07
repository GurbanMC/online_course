@extends('admin.layouts.app')
@section('title')
    @lang('app.dashboard')
@endsection
@section('content')
    <div class="row g-3 mb-4">
        @foreach($modals as $modal)
            <div class="col-8 col-md-6 col-lg-4 col-xl-3">
                <a href="{{ route('admin.' . $modal['name'] . '.index') }}" class="text-decoration-none text-dark">
                    <div class="border rounded-4 text-light fw-bold p-3" style="background-color: {{$modal['color']}}">
                        <div class="fs-5">
                            @lang('app.' . $modal['name'])
                        </div>
                        <div class="fs-3 fw-semibold text-end">
                            {{ $modal['total'] }}
                        </div>
                    </div>
                </a>
            </div>
        @endforeach
    </div>
@endsection
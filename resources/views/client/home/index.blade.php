@extends('client.layouts.app')
@section('title')
    @lang('app.app-name')
@endsection
@section('content')
    <div class="bg-danger text-light text-center fs-5 fw-bold p-3">
        @foreach($categories->sortBy('sort_order')->sortBy('parent.sort_order') as $category)
            @if($category->count() > 0)
                @include('client.home.categories')
            @endif
        @endforeach
    </div>
    <div class="container-lg">
        @include('client.home.courses')
    </div>
@endsection
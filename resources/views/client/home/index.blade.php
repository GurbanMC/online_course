@extends('client.layouts.app')
@section('title')
    @lang('app.app-name')
@endsection
@section('content')
    @foreach($categories->sortBy('sort_order')->sortBy('parent.sort_order') as $category)
        @if($category->count() > 0)
            @include('client.home.categories')
        @endif
    @endforeach
@endsection
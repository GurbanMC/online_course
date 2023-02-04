@extends('admin.layouts.app')
@section('title')
    @lang('app.dashboard')
@endsection
@section('content')
    <div class="row g-3 mb-4">
        @foreach($modals as $modal)
            <div class="col-6 col-md-4 col-lg-3 col-xl-2">
                <a href="{{ route('admin.' . $modal['name'] . '.index') }}" class="text-decoration-none text-dark">
                    <div class="border bg-light rounded p-3">
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

    <div class="row">
        <div class="col-lg-4">
            <div class="card">
                <a href="{{ route('admin.orders.index', ['status' => 0]) }}" class="d-flex justify-content-between align-items-center text-decoration-none card-header">
                    <div>@lang('app.pending') - @lang('app.orders')</div>
                    <div class="fs-5 fw-semibold">{{ $pendingOrdersCount }}</div>
                </a>
                <div class="card-body small p-1">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped table-sm mb-0">
                            <tbody>
                            @forelse($pendingOrders as $obj)
                                <tr>
                                    <td width="40%">
                                        <i class="bi-geo-alt-fill text-secondary"></i>
                                        {{ $obj->location->getName() }}
                                    </td>
                                    <td width="40%">
                                        @if($obj->customer_id)
                                            <i class="bi-person-square text-secondary"></i>
                                            <a href="{{ route('admin.customers.show', $obj->customer_id) }}" class="text-decoration-none">
                                                {{ $obj->customer_name }}
                                                <i class="bi-box-arrow-up-right"></i>
                                            </a>
                                        @else
                                            <i class="bi-person-square text-secondary"></i>
                                            {{ $obj->customer_name }}
                                        @endif
                                    </td>
                                    <td width="20%">
                                        {{ number_format($obj->total_price, 2, '.', ' ') }}
                                        <small>TMT</small>
                                    </td>
                                </tr>
                            @empty
                                <tr class="table-warning">
                                    <td>Not found</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card">
                <a href="{{ route('admin.orders.index', ['status' => 1]) }}" class="d-flex justify-content-between align-items-center text-decoration-none card-header">
                    <div>@lang('app.accepted') - @lang('app.orders')</div>
                    <div class="fs-5 fw-semibold">{{ $acceptedOrdersCount }}</div>
                </a>
                <div class="card-body small p-1">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped table-sm mb-0">
                            <tbody>
                            @forelse($acceptedOrders as $obj)
                                <tr>
                                    <td width="40%">
                                        <i class="bi-geo-alt-fill text-secondary"></i>
                                        {{ $obj->location->getName() }}
                                    </td>
                                    <td width="40%">
                                        @if($obj->customer_id)
                                            <i class="bi-person-square text-secondary"></i>
                                            <a href="{{ route('admin.customers.show', $obj->customer_id) }}" class="text-decoration-none">
                                                {{ $obj->customer_name }}
                                                <i class="bi-box-arrow-up-right"></i>
                                            </a>
                                        @else
                                            <i class="bi-person-square text-secondary"></i>
                                            {{ $obj->customer_name }}
                                        @endif
                                    </td>
                                    <td width="20%">
                                        {{ number_format($obj->total_price, 2, '.', ' ') }}
                                        <small>TMT</small>
                                    </td>
                                </tr>
                            @empty
                                <tr class="table-warning">
                                    <td>Not found</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card">
                <a href="{{ route('admin.orders.index', ['status' => 2]) }}" class="d-flex justify-content-between align-items-center text-decoration-none card-header">
                    <div>@lang('app.sent') - @lang('app.orders')</div>
                    <div class="fs-5 fw-semibold">{{ $sentOrdersCount }}</div>
                </a>
                <div class="card-body small p-1">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped table-sm mb-0">
                            <tbody>
                            @forelse($sentOrders as $obj)
                                <tr>
                                    <td width="40%">
                                        <i class="bi-geo-alt-fill text-secondary"></i>
                                        {{ $obj->location->getName() }}
                                    </td>
                                    <td width="40%">
                                        @if($obj->customer_id)
                                            <i class="bi-person-square text-secondary"></i>
                                            <a href="{{ route('admin.customers.show', $obj->customer_id) }}" class="text-decoration-none">
                                                {{ $obj->customer_name }}
                                                <i class="bi-box-arrow-up-right"></i>
                                            </a>
                                        @else
                                            <i class="bi-person-square text-secondary"></i>
                                            {{ $obj->customer_name }}
                                        @endif
                                    </td>
                                    <td width="20%">
                                        {{ number_format($obj->total_price, 2, '.', ' ') }}
                                        <small>TMT</small>
                                    </td>
                                </tr>
                            @empty
                                <tr class="table-warning">
                                    <td>Not found</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@extends('admin.layouts.app')
@section('title')
    @lang('app.orders')
@endsection
@section('content')
    <div class="h4 mb-2 border-bottom">
        @lang('app.orders')
    </div>
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            @include('admin.order.filter')
        </div>
    </div>

    <!-- Order Statistics -->
    <div class="row">

        <!-- Income (sum of completed orders) -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card shadow h-100 py-2">
                <div class="card-body">
                    <div class="col me-2">
                        <div class="small fw-bold text-primary text-uppercase mb-1">
                            @lang('app.totalIncome')
                            (<span class="text-muted text-lowercase">
                                @lang($statistic ? 'app.' . $statistic : 'app.total')
                            </span>)
                        </div>
                        <div class="fs-5 mb-0 fw-bold">
                            {{ number_format($orderStats[3]['totalPrice'], '2', ',', '.') }}
                            <small class="font-monospace">TMT</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Completed Orders -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card shadow h-100 py-2">
                <div class="card-body">
                    <div class="col me-2">
                        <div class="small fw-bold text-success text-uppercase mb-1">
                            @lang('app.completed')
                            (<span class="text-muted text-lowercase">
                                @lang($statistic ? 'app.' . $statistic : 'app.total')
                            </span>)
                        </div>
                        <div class="fs-5 mb-0 fw-bold">{{ $orderStats[3]['totalOrders'] }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Accepted Orders -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card shadow h-100 py-2">
                <div class="card-body">
                    <div class="col me-2">
                        <div class="small fw-bold text-info text-uppercase mb-1">
                            @lang('app.accepted')
                            (<span class="text-muted text-lowercase">
                                @lang($statistic ? 'app.' . $statistic : 'app.total')
                            </span>)
                        </div>
                        <div class="fs-5 mb-0 fw-bold">{{ $orderStats[2]['totalOrders'] }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Orders -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card shadow h-100 py-2">
                <div class="card-body">
                    <div class="col me-2">
                        <div class="small fw-bold text-warning text-uppercase mb-1">
                            @lang('app.pending')
                            (<span class="text-muted text-lowercase">
                                @lang($statistic ? 'app.' . $statistic : 'app.total')
                            </span>)                        </div>
                        <div class="fs-5 mb-0 fw-bold">{{ $orderStats[0]['totalOrders'] }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Order Statistics -->

    <div class="table-responsive">
        <table class="table table-hover table-striped">
            <thead>
            <tr>
                <th scope="col">@lang('app.id')</th>
                <th scope="col">@lang('app.code')</th>
                <th scope="col" width="30%">@lang('app.location')</th>
                <th scope="col">@lang('app.customer')</th>
                <th scope="col">@lang('app.price')</th>
                <th scope="col">@lang('app.status')</th>
            </tr>
            </thead>
            <tbody>
            @foreach($objs as $obj)
                <tr class="{{ $obj->status === 4 ? 'table-danger' : '' }} {{ $obj->trashed() ? 'opacity-50' : '' }}">
                    <td>{{ $obj->id }}</td>
                    <td>
                        @if( $obj->trashed() )
                            <div class="font-monospace">{{ $obj->code }}</div>
                            <span class="badge bg-danger">
                                @lang('app.trashed')
                            </span>
                        @else
                            <a href="{{ route('admin.orders.show', $obj->id) }}" class="text-decoration-none">
                                <span class="font-monospace">{{ $obj->code }}</span>
                                <i class="bi-box-arrow-up-right"></i>
                            </a>
                        @endif
                        <div class="small text-secondary">
                            <img src="{{ asset('img/flag/' . $obj->language() . '.png') }}" alt="Language" height="12" class="mb-1">
                            {{ $obj->platform() }}
                        </div>
                    </td>
                    <td>
                        <div class="mb-1">
                            <i class="bi-geo-alt-fill text-secondary"></i>
                            @if($obj->location->parent_id)
                                {{ $obj->location->parent->getName() }},
                            @endif
                            {{ $obj->location->getName() }}
                        </div>
                        <div class="small">
                            <i class="bi-geo-fill text-secondary"></i> {{ $obj->customer_address }}
                        </div>
                        @if($obj->customer_note)
                            <div class="small">
                                <i class="bi-sticky-fill text-warning"></i> {{ $obj->customer_note }}
                            </div>
                        @endif
                    </td>
                    <td>
                        @if($obj->customer_id)
                            <div class="mb-1">
                                <i class="bi-person-square text-secondary"></i>
                                <a href="{{ route('admin.customers.show', $obj->customer_id) }}" class="text-decoration-none">
                                    {{ $obj->customer_name }}
                                    @if($obj->customer_name != $obj->customer->name)
                                        ({{ $obj->customer->name }})
                                    @endif
                                    <i class="bi-box-arrow-up-right"></i>
                                </a>
                            </div>
                        @else
                            <div class="mb-1">
                                <i class="bi-person-square text-secondary"></i>
                                {{ $obj->customer_name }}
                            </div>
                        @endif
                        <div>
                            <i class="bi-telephone-fill text-success"></i>
                            <a href="tel:+993{{ $obj->customer_phone }}" class="text-decoration-none">
                                +993 {{ $obj->customer_phone }}
                            </a>
                        </div>
                    </td>
                    <td>
                        <div class="fs-5 mb-1">
                            {{ number_format($obj->total_price, 2, '.', ' ') }}
                            <small>TMT</small>
                        </div>
                        <div class="text-danger fw-semibold">
                            <i class="bi-box-fill text-secondary"></i> {{ $obj->order_products_count }}
                        </div>
                    </td>
                    <td>
                        <div class="mb-1">
                            <span class="badge bg-{{ $obj->statusColor() }}">{{ $obj->status() }}</span>
                        </div>
                        <div>{{ $obj->payment() }}</div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <div class="mb-3">
            {{ $objs->links() }}
        </div>
    </div>
@endsection
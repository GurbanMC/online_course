@extends('admin.layouts.app')
@section('title')
    @lang('app.order')
@endsection
@section('content')
    <div class="row d-flex flex-wrap justify-content-between border-bottom mb-3">
        <div class="row col-auto">
            <div class="col-auto mb-2 fw-semibold">
                <a href="{{ route('admin.orders.index') }}" class="btn btn-sm bi-arrow-left-short text-dark border rounded px-2"></a>
            </div>
            <div class="col-auto pt-1 fw-semibold">
                @lang('app.order') <small class="text-primary">#{{ $obj->code }}</small> -
                <span class="badge text-dark bg-{{ $obj->statusColor() }} small">{{ $obj->status() }}</span>
            </div>
        </div>
        <div class="col-auto pt-1">
            <i class="bi-calendar2-date"></i> {{ $obj->created_at->diffForHumans() . ', ' . $obj->created_at->format('H:i, M d, Y') }}
        </div>
        <div class="col-auto">
            <div class="btn-group" role="group">
                @if( !in_array($obj->status, [3,4]) )
                    <a href="{{ route('admin.orders.edit', $obj->id) }}" class="btn btn-sm btn-outline-success">
                        <i class="bi-pencil"></i>
                    </a>
                @endif
                <form action="{{ route('admin.orders.destroy', $obj->id) }}" method="POST" id="orderDestroyId-{{ $obj->id }}">
                    @method('DELETE')
                    @csrf
                    @honeypot
                </form>
                <a href="javascript:void(0);" class="btn btn-sm btn-outline-danger" onclick="event.preventDefault(); $('form#orderDestroyId-{{ $obj->id }}').submit();">
                    <i class="bi-trash"></i>
                </a>
            </div>
        </div>
    </div>

    @if( $obj->status === 4 )
        <div class="alert alert-warning d-flex align-items-center" role="alert">
            <i class="bi-exclamation-triangle-fill flex-shrink-0 me-2" role="img"></i>
            <div>
                @lang('app.orderCanceledError', ['name' => $obj->customer_name])
            </div>
        </div>
    @endif

    <div class="row">
        <div class="col-9">

            <div class="table-responsive border rounded">
                <table class="table table-sm table-hover table-striped table-borderless">
                    <thead>
                    <tr class="border-bottom">
                        <th scope="col">@lang('app.id')</th>
                        <th scope="col">@lang('app.name')</th>
                        <th scope="col">@lang('app.price')</th>
                        <th scope="col">@lang('app.quantity')</th>
                        <th scope="col">@lang('app.discount')</th>
                        <th scope="col">@lang('app.totalPrice')</th>
                        @if( $obj->status < 2 )
                            <th scope="col">@lang('app.action')</th>
                        @endif
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($obj->orderProducts as $orderProduct)
                        <tr>
                            <td>
                                <div class="fw-semibold">
                                    {{ $orderProduct->id }}
                                </div>
                            </td>
                            <td>
                                <a href="{{ route('admin.products.show', $orderProduct->product_id) }}" class="text-decoration-none">
                                    {{ $orderProduct->product->getFullName() }}
                                </a>
                            </td>
                            <td>
                                <span class="fw-semibold">
                                    {{ $orderProduct->product->getPrice() }}
                                </span>
                                <small class="font-monospace">TMT</small>
                            </td>
                            <td>
                                x
                                <span class="fw-semibold">
                                    {{ $orderProduct->quantity }}
                                </span>
                            </td>
                            <td>
                                @if($orderProduct->discount_percent)
                                    <span class="fw-semibold text-success">
                                        - {{ $orderProduct->discount_percent }}%
                                    </span>
                                @endif
                            </td>
                            <td>
                                <span class="fw-semibold">
                                    {{ number_format($orderProduct->total_price, '2', '.', '') }}
                                </span>
                                <small class="font-monospace">TMT</small>
                            </td>
                            @if( $obj->status < 2 )
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.orderProducts.edit', $orderProduct->id) }}" class="btn btn-sm btn-outline-success">
                                            <i class="bi-pencil"></i>
                                        </a>
                                        <button type="button" data-bs-target="#delete{{ $orderProduct->id }}" data-bs-toggle="modal" class="btn btn-sm btn-outline-danger">
                                            <i class="bi-trash"></i>
                                        </button>
                                    </div>

                                    <div class="modal fade" id="delete{{ $orderProduct->id }}" tabindex="-1" aria-labelledby="delete{{ $orderProduct->id }}Label" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <div class="modal-title fs-6 fw-semibold" id="delete{{ $orderProduct->id }}Label">
                                                        @lang('app.opDelete', ['id' => $orderProduct->id])
                                                    </div>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-footer">
                                                    <form action="{{ route('admin.orderProducts.destroy', $orderProduct->id) }}" method="post">
                                                        @method('DELETE')
                                                        @csrf
                                                        <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">@lang('app.no')</button>
                                                        <button type="submit" class="btn btn-secondary btn-sm"><i class="bi-trash"></i> @lang('app.yes')</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </td>
                            @endif
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            <div class="table border rounded mt-4">
                <div class="row flex-wrap justify-content-between m-1 text-secondary">
                    <div class="col-auto">
                        <div>
                            @lang('app.subtotal')
                        </div>
                    </div>
                    <div class="col-auto">
                        <div>
                            {{ $obj->order_products_count }}
                            @if($obj->order_products_count > 1)
                                @lang('app.items')
                            @else
                                @lang('app.item')
                            @endif
                        </div>
                    </div>
                    <div class="col-auto">
                        <div class="text-end">
                            {{ number_format($obj->total_price, '2', '.', ',') }} <small class="font-monospace">TMT</small>
                        </div>
                    </div>
                </div>
                <div class="row flex-wrap justify-content-between m-1 text-secondary">
                    <div class="col-auto">
                        <div>
                            @lang('app.delivery')
                        </div>
                    </div>
                    <div class="col-auto text-end">
                        <div>
                            {{ number_format($obj->delivery_fee, '2', '.', ',') }} <small class="font-monospace">TMT</small>
                        </div>
                    </div>
                </div>
                <div class="row flex-wrap justify-content-between m-1 fw-bolder border-bottom">
                    <div class="col-auto">
                        <div>
                            @lang('app.total')
                        </div>
                    </div>
                    <div class="col-auto text-end">
                        <div>
                            {{ number_format($obj->total_price + $obj->delivery_fee, '2', '.', ',') }} <small class="font-monospace">TMT</small>
                        </div>
                    </div>
                </div>
                <div class="row flex-wrap justify-content-between m-1 text-secondary">
                    <div class="col-auto">
                        <div>
                            @lang('app.paid')
                        </div>
                    </div>
                    <div class="col-auto text-end">
                        <div>
                            @if( in_array($obj->status, [0,1,4]) )
                                {{ number_format(0, '2', '.', ',') }} <small class="font-monospace">TMT</small>
                            @else
                                {{ number_format($obj->total_price + $obj->delivery_fee, '2', '.', ',') }} <small class="font-monospace">TMT</small>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="table border rounded mt-4">
                <div class="m-3">
                    <div class="fw-semibold h5 text-uppercase mb-3" style="font-family: 'Microsoft Sans Serif';">@lang('app.shippingInformation')</div>

                    <div class="font-monospace fs-6 fw-normal">
                        <i class="bi-truck">
                            @lang('app.deliveryService')
                        </i>
                        - <span class="text-uppercase">{{ $obj->location->parent_id ? $obj->location->parent->getName() : $obj->location->getName() }}</span>
                    </div>

                    <div class="font-monospace fs-6 fw-normal">
                        <i class="bi-buildings-fill">
                            @lang('app.deliveryAddress')
                        </i>
                        - <span>{{ $obj->location->getName() . ', ' . $obj->customer_address }}</span>
                    </div>

                </div>
            </div>

        </div>
        <div class="col-3">

            <div class="border rounded p-2">
                @if( isset($obj->customer_note) )
                    <div class="border-bottom">
                        <div class="fw-bold">
                            @lang('app.notes')
                        </div>
                        <p class="small text-secondary">{{ $obj->customer_note }}</p>
                    </div>
                @endif
                <div>
                    <div class="fw-bold my-2 text-uppercase">
                        @lang('app.additionalDetails')
                    </div>
                    <div>
                        <span class="fw-semibold">@lang('app.platform')</span> -
                        <small class="text-secondary">{{ $obj->platform() }}</small>
                    </div>
                    <div>
                        <span class="fw-semibold">@lang('app.language')</span> -
                        <small class="text-secondary">
                            <img src="{{ asset('img/flag/' . $obj->language() . '.png') }}" alt="" width="32" class="img-thumbnail">
                        </small>
                    </div>
                    <div>
                        <span class="fw-semibold">@lang('app.payment')</span> -
                        <small class="text-secondary">{{ $obj->payment() }}</small>
                    </div>
                </div>
            </div>

            <div class="border rounded p-2 mt-4">
                <div class="">
                    <div class="fw-bold mb-1 text-uppercase">
                        @lang('app.customerInformation')
                    </div>
                    @if( isset($obj->customer_id) )
                        <a href="{{ route('admin.customers.show', $obj->customer_id) }}" class="small text-secondary">{{ $obj->customer_name }}</a>
                    @else
                        <div>
                            <i class="bi-person-circle text-primary"></i>
                            <span class="small text-secondary">
                                {{ $obj->customer_name }}
                            </span>
                        </div>
                    @endif

                    <div>
                        <i class="bi-wallet-fill text-danger"></i>
                        <span class="small text-secondary">
                                @lang('app.totalOrders'): {{ $customerOrderCount }}
                            </span>
                    </div>

                    <div>
                        <i class="bi-telephone-outbound-fill text-success"></i>
                        <a href="tel:+993{{ $obj->customer_phone }}" class="small text-secondary">
                            {{ '+993 ' . $obj->customer_phone }}
                        </a>
                    </div>

                    <div>
                        <i class="bi-geo-alt-fill"></i>
                        <span class="small text-secondary">
                                {{ (isset($obj->location->parent_id) ? $obj->location->parent->getName() : '') . ', ' . $obj->location->getName() . ', ' . $obj->customer_address }}
                            </span>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection

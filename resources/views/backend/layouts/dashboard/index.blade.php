@extends('backend.app')

@section('title', 'Dashboard')

@section('content')
    <div class="row">
        <div class="col">
            <div class="h-100">
                <div class="row mb-3 pb-1">
                    <div class="col-12">
                        <div class="d-flex align-items-lg-center flex-lg-row flex-column">
                            <div class="flex-grow-1">
                                <h4 class="fs-16 mb-1">Welcome Back, {{ auth()->user()->name ?? 'Admin' }}!</h4>
                                <p class="text-muted mb-0">Here's what's happening with your store today.</p>
                            </div>
                            <div class="mt-3 mt-lg-0">
                                <a href="{{ route('admin.orders.index') }}" class="btn btn-primary">
                                    <i class="ri-shopping-cart-2-line align-middle me-1"></i> View All Orders
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Statistics Cards Row -->
                <div class="row">
                    <!-- Total Earnings -->
                    <div class="col-xl-3 col-md-6">
                        <div class="card card-animate">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1 overflow-hidden">
                                        <p class="text-uppercase fw-medium text-muted text-truncate mb-0"> Total Earnings</p>
                                    </div>
                                </div>
                                <div class="d-flex align-items-end justify-content-between mt-4">
                                    <div>
                                        <h4 class="fs-22 fw-semibold ff-secondary mb-4">${{ number_format($totalEarnings, 2) }}</h4>
                                        <a href="{{ route('admin.orders.index') }}" class="text-decoration-underline text-muted">View orders</a>
                                    </div>
                                    <div class="avatar-sm flex-shrink-0">
                                        <span class="avatar-title bg-success-subtle rounded fs-3">
                                            <i class="bx bx-dollar-circle text-success"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Total Orders -->
                    <div class="col-xl-3 col-md-6">
                        <div class="card card-animate">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1 overflow-hidden">
                                        <p class="text-uppercase fw-medium text-muted text-truncate mb-0">Total Orders</p>
                                    </div>
                                </div>
                                <div class="d-flex align-items-end justify-content-between mt-4">
                                    <div>
                                        <h4 class="fs-22 fw-semibold ff-secondary mb-4">{{ number_format($totalOrders) }}</h4>
                                        <a href="{{ route('admin.orders.index') }}" class="text-decoration-underline text-muted">View all orders</a>
                                    </div>
                                    <div class="avatar-sm flex-shrink-0">
                                        <span class="avatar-title bg-info-subtle rounded fs-3">
                                            <i class="bx bx-shopping-bag text-info"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Total Products -->
                    <div class="col-xl-3 col-md-6">
                        <div class="card card-animate">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1 overflow-hidden">
                                        <p class="text-uppercase fw-medium text-muted text-truncate mb-0">Total Products</p>
                                    </div>
                                </div>
                                <div class="d-flex align-items-end justify-content-between mt-4">
                                    <div>
                                        <h4 class="fs-22 fw-semibold ff-secondary mb-4">{{ number_format($totalProducts) }}</h4>
                                        <a href="{{ route('admin.products.index') }}" class="text-decoration-underline text-muted">View products</a>
                                    </div>
                                    <div class="avatar-sm flex-shrink-0">
                                        <span class="avatar-title bg-warning-subtle rounded fs-3">
                                            <i class="bx bx-box text-warning"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Total Customers -->
                    <div class="col-xl-3 col-md-6">
                        <div class="card card-animate">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1 overflow-hidden">
                                        <p class="text-uppercase fw-medium text-muted text-truncate mb-0">Total Customers</p>
                                    </div>
                                </div>
                                <div class="d-flex align-items-end justify-content-between mt-4">
                                    <div>
                                        <h4 class="fs-22 fw-semibold ff-secondary mb-4">{{ number_format($totalCustomers) }}</h4>
                                        <span class="text-muted">Registered users</span>
                                    </div>
                                    <div class="avatar-sm flex-shrink-0">
                                        <span class="avatar-title bg-primary-subtle rounded fs-3">
                                            <i class="bx bx-user-circle text-primary"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Orders Table -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header align-items-center d-flex">
                                <h4 class="card-title mb-0 flex-grow-1">Recent Orders</h4>
                                <div class="flex-shrink-0">
                                    <a href="{{ route('admin.orders.index') }}" class="btn btn-soft-info btn-sm">View All Orders <i class="ri-arrow-right-line align-middle"></i></a>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive table-card">
                                    <table class="table table-hover table-centered align-middle table-nowrap mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th scope="col">Invoice No</th>
                                                <th scope="col">Customer</th>
                                                <th scope="col">Product</th>
                                                <th scope="col">Amount</th>
                                                <th scope="col">Payment Status</th>
                                                <th scope="col">Order Status</th>
                                                <th scope="col">Date</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($recentOrders as $order)
                                                <tr>
                                                    <td>
                                                        <span class="fw-semibold">{{ $order->invoice_no ?? ('#ORD-' . $order->id) }}</span>
                                                    </td>
                                                    <td>
                                                        @if($order->customer)
                                                            {{ $order->customer->name }}
                                                        @elseif($order->customerInfo)
                                                            {{ trim($order->customerInfo->first_name . ' ' . $order->customerInfo->last_name) }}
                                                        @else
                                                            N/A
                                                        @endif
                                                    </td>
                                                    <td>
                                                        {{ $order->product->title ?? 'N/A' }}
                                                        @if($order->productVariation && $order->productVariation->length)
                                                            <small class="text-muted">({{ $order->productVariation->length }})</small>
                                                        @endif
                                                    </td>
                                                    <td>${{ number_format($order->grand_total, 2) }}</td>
                                                    <td>
                                                        <span class="badge {{ $order->payment_status == 'paid' ? 'bg-success' : ($order->payment_status == 'failed' ? 'bg-danger' : 'bg-warning') }}">
                                                            {{ ucfirst($order->payment_status) }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <span class="badge {{ $order->order_status == 'completed' ? 'bg-success' : ($order->order_status == 'processing' ? 'bg-info' : ($order->order_status == 'cancelled' ? 'bg-danger' : 'bg-warning')) }}">
                                                            {{ ucfirst($order->order_status) }}
                                                        </span>
                                                    </td>
                                                    <td>{{ $order->created_at ? $order->created_at->format('d M, Y') : 'N/A' }}</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="7" class="text-center py-4 text-muted">No recent orders found.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection

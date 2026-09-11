@extends('backend.app')

@section('title', 'Order Details - ' . ($order->invoice_no ?? '#ORD-' . $order->id))

@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                <h4 class="mb-sm-0">Order Details</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.orders.index') }}">Orders</a></li>
                        <li class="breadcrumb-item active">Details</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row">
        <!-- Order Invoice Main Card -->
        <div class="col-xl-9 col-lg-8">
            <div class="card" id="invoiceCard">
                <div class="card-header border-bottom-dashed p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h4 class="fw-bold mb-1">Invoice: {{ $order->invoice_no ?? '#ORD-' . $order->id }}</h4>
                            <p class="text-muted mb-0">Date: {{ $order->created_at ? $order->created_at->format('d M, Y - h:i A') : 'N/A' }}</p>
                        </div>
                        <div class="text-end">
                            <span class="badge {{ $order->order_status == 'completed' ? 'bg-success' : ($order->order_status == 'processing' ? 'bg-info' : ($order->order_status == 'cancelled' ? 'bg-danger' : 'bg-warning')) }} fs-13 px-3 py-2">
                                Order Status: {{ ucfirst($order->order_status) }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-lg-3 col-6">
                            <p class="text-muted mb-2 text-uppercase fw-semibold fs-12">Invoice No</p>
                            <h5 class="fs-14 mb-0">{{ $order->invoice_no ?? '#ORD-' . $order->id }}</h5>
                        </div>
                        <div class="col-lg-3 col-6">
                            <p class="text-muted mb-2 text-uppercase fw-semibold fs-12">Payment Method</p>
                            <h5 class="fs-14 mb-0 text-uppercase">{{ $order->payment_method }}</h5>
                        </div>
                        <div class="col-lg-3 col-6">
                            <p class="text-muted mb-2 text-uppercase fw-semibold fs-12">Payment Status</p>
                            <span class="badge {{ $order->payment_status == 'paid' ? 'bg-success-subtle text-success' : ($order->payment_status == 'failed' ? 'bg-danger-subtle text-danger' : 'bg-warning-subtle text-warning') }} fs-12">
                                {{ ucfirst($order->payment_status) }}
                            </span>
                        </div>
                        <div class="col-lg-3 col-6">
                            <p class="text-muted mb-2 text-uppercase fw-semibold fs-12">Transaction ID</p>
                            <h5 class="fs-14 mb-0">{{ $order->transaction_id ?? 'N/A' }}</h5>
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- Customer & Delivery Address Info -->
                    <div class="row">
                        <div class="col-sm-6">
                            <h6 class="text-muted text-uppercase fw-semibold fs-12 mb-3">Customer Details</h6>
                            @if($order->customerInfo)
                                <h5 class="fs-15 font-weight-bold mb-1">{{ $order->customerInfo->first_name }} {{ $order->customerInfo->last_name }}</h5>
                                <p class="text-muted mb-1"><i class="ri-mail-line me-1"></i> {{ $order->customerInfo->email ?? 'N/A' }}</p>
                            @elseif($order->customer)
                                <h5 class="fs-15 font-weight-bold mb-1">{{ $order->customer->name }}</h5>
                                <p class="text-muted mb-1"><i class="ri-mail-line me-1"></i> {{ $order->customer->email ?? 'N/A' }}</p>
                            @else
                                <p class="text-muted">No Customer Data Found</p>
                            @endif
                        </div>

                        <div class="col-sm-6">
                            <h6 class="text-muted text-uppercase fw-semibold fs-12 mb-3">Shipping Address</h6>
                            @if($order->customerInfo)
                                <p class="text-muted mb-1">{{ $order->customerInfo->address_line_one }}</p>
                                @if($order->customerInfo->address_line_two)
                                    <p class="text-muted mb-1">{{ $order->customerInfo->address_line_two }}</p>
                                @endif
                                <p class="text-muted mb-0">
                                    {{ implode(', ', array_filter([$order->customerInfo->sub_burb, $order->customerInfo->state, $order->customerInfo->post_code, $order->customerInfo->country_region])) }}
                                </p>
                            @else
                                <p class="text-muted">No Shipping Address Info</p>
                            @endif
                        </div>
                    </div>

                    <div class="table-responsive mt-4">
                        <table class="table table-borderless text-center table-nowrap align-middle mb-0">
                            <thead class="table-light">
                                <tr class="text-muted">
                                    <th scope="col" style="width: 50px;">#</th>
                                    <th scope="col" class="text-start">Product Details</th>
                                    <th scope="col">Rate</th>
                                    <th scope="col">Quantity</th>
                                    <th scope="col" class="text-end">Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th scope="row">01</th>
                                    <td class="text-start">
                                        <h5 class="fs-14 mb-1">{{ $order->product->title ?? 'N/A' }}</h5>
                                        @if($order->productVariation && $order->productVariation->length)
                                            <p class="text-muted mb-0">Length / Size: <strong>{{ $order->productVariation->length }}</strong></p>
                                        @endif
                                    </td>
                                    <td>${{ number_format($order->sub_total / max($order->quantity, 1), 2) }}</td>
                                    <td>{{ $order->quantity }}</td>
                                    <td class="text-end">${{ number_format($order->sub_total, 2) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Calculation Summary -->
                    <div class="border-top border-top-dashed mt-4 pt-3">
                        <div class="row justify-content-end">
                            <div class="col-md-5 col-sm-7">
                                <table class="table table-borderless table-sm mb-0">
                                    <tbody>
                                        <tr>
                                            <td>Sub Total :</td>
                                            <td class="text-end">${{ number_format($order->sub_total, 2) }}</td>
                                        </tr>
                                        <tr>
                                            <td>Tax :</td>
                                            <td class="text-end">${{ number_format($order->tax, 2) }}</td>
                                        </tr>
                                        <tr>
                                            <td>Discount :</td>
                                            <td class="text-end">-${{ number_format($order->discount, 2) }}</td>
                                        </tr>
                                        <tr class="border-top border-top-dashed font-weight-bold fs-16">
                                            <th scope="row">Grand Total :</th>
                                            <th class="text-end">${{ number_format($order->grand_total, 2) }}</th>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    @if($order->notes)
                        <div class="mt-4 p-3 bg-light rounded">
                            <h6 class="fs-13 fw-semibold text-muted text-uppercase mb-1">Customer Notes:</h6>
                            <p class="mb-0 text-dark">{{ $order->notes }}</p>
                        </div>
                    @endif

                </div>
            </div>
        </div>

        <!-- Right Side Order Status & Actions Card -->
        <div class="col-xl-3 col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Order Actions</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="order_status_update" class="form-label font-weight-bold">Change Order Status</label>
                        <select class="form-select" id="order_status_update">
                            <option value="pending" {{ $order->order_status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="processing" {{ $order->order_status == 'processing' ? 'selected' : '' }}>Processing</option>
                            <option value="completed" {{ $order->order_status == 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="cancelled" {{ $order->order_status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>

                    <button type="button" class="btn btn-success w-100 mb-2" id="updateStatusBtn">
                        <i class="ri-save-line align-middle me-1"></i> Update Status
                    </button>

                    <button type="button" onclick="window.print()" class="btn btn-soft-secondary w-100 mb-2">
                        <i class="ri-printer-line align-middle me-1"></i> Print Invoice
                    </button>

                    <a href="{{ route('admin.orders.index') }}" class="btn btn-soft-primary w-100">
                        <i class="ri-arrow-left-line align-middle me-1"></i> Back to Orders
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#updateStatusBtn').click(function() {
                let newStatus = $('#order_status_update').val();
                let btn = $(this);
                btn.prop('disabled', true).html('<i class="ri-loader-4-line spin align-middle me-1"></i> Updating...');

                $.ajax({
                    url: "{{ url('admin/orders/status/' . $order->id) }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        order_status: newStatus
                    },
                    success: function(response) {
                        btn.prop('disabled', false).html('<i class="ri-save-line align-middle me-1"></i> Update Status');
                        if(response.success) {
                            toastr.success(response.message);
                            setTimeout(function() {
                                location.reload();
                            }, 800);
                        }
                    },
                    error: function() {
                        btn.prop('disabled', false).html('<i class="ri-save-line align-middle me-1"></i> Update Status');
                        toastr.error('Failed to update status.');
                    }
                });
            });
        });
    </script>
@endpush

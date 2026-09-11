@extends('backend.app')
@section('title', 'Orders List')

@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                <h4 class="mb-sm-0">Orders List</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Orders List</li>
                    </ol>
                </div>

            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Orders List</h4>
                </div>

                <div class="card-body">
                    <table class="table table-bordered table-striped dt-responsive nowrap align-middle" id="orderTable" style="width: 100%;">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Invoice No</th>
                                <th>Customer</th>
                                <th>Product & Variation</th>
                                <th>Price</th>
                                <th>Order Status</th>
                                <th>Payment Status</th>
                                <th>Order Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>

            </div>
        </div>
        <!--end col-->
    </div>
    <!--end row-->
@endsection

@push('scripts')
    <script>
        $(function() {
            let table = $('#orderTable').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: "{{ route('admin.orders.index') }}",
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'invoice_no', name: 'invoice_no' },
                    { data: 'customer_id', name: 'customer_id' },
                    { data: 'product_and_variation_info', name: 'product_and_variation_info' },
                    { data: 'price', name: 'price' },
                    { data: 'order_status', name: 'order_status', orderable: false, searchable: false },
                    { data: 'payment_status', name: 'payment_status', orderable: false, searchable: false },
                    { data: 'created_at', name: 'created_at' },
                    { data: 'actions', name: 'actions', orderable: false, searchable: false }
                ]
            });

            // Handle status change dropdown AJAX
            $(document).on('change', '.order-status-select', function() {
                let orderId = $(this).data('id');
                let newStatus = $(this).val();

                $.ajax({
                    url: "{{ url('admin/orders/status') }}/" + orderId,
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        order_status: newStatus
                    },
                    success: function(response) {
                        if (response.success) {
                            toastr.success(response.message);
                            table.ajax.reload(null, false);
                        }
                    },
                    error: function(xhr) {
                        toastr.error('Failed to update order status.');
                    }
                });
            });

            // Handle delete order AJAX
            $(document).on('click', '.delete-order-btn', function() {
                let orderId = $(this).data('id');

                if (confirm('Are you sure you want to delete this order?')) {
                    $.ajax({
                        url: "{{ url('admin/orders') }}/" + orderId,
                        type: "DELETE",
                        data: {
                            _token: "{{ csrf_token() }}"
                        },
                        success: function(response) {
                            if (response.success) {
                                toastr.success(response.message);
                                table.ajax.reload(null, false);
                            }
                        },
                        error: function(xhr) {
                            toastr.error('Failed to delete order.');
                        }
                    });
                }
            });
        });
    </script>
@endpush

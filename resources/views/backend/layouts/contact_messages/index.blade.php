@extends('backend.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
            <h4 class="mb-sm-0">Contact Messages</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                    <li class="breadcrumb-item active">Contact Messages</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header align-items-center d-flex">
                <h4 class="card-title mb-0 flex-grow-1">Contact Messages List</h4>
            </div>
            <div class="card-body">
                <table class="table table-bordered dt-responsive nowrap align-middle" id="dataTable" style="width:100%">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Telephone</th>
                            <th>Nation</th>
                            <th>Message</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- View Message Modal -->
<div class="modal fade" id="viewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Contact Message Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="fw-bold">First Name:</label>
                        <p id="view_name" class="text-muted mb-0"></p>
                    </div>
                    <div class="col-md-6">
                        <label class="fw-bold">Surname:</label>
                        <p id="view_surname" class="text-muted mb-0"></p>
                    </div>
                    <div class="col-md-6">
                        <label class="fw-bold">Email:</label>
                        <p id="view_email" class="text-muted mb-0"></p>
                    </div>
                    <div class="col-md-6">
                        <label class="fw-bold">Telephone:</label>
                        <p id="view_telephone" class="text-muted mb-0"></p>
                    </div>
                    <div class="col-md-6">
                        <label class="fw-bold">Nation:</label>
                        <p id="view_nation" class="text-muted mb-0"></p>
                    </div>
                    <div class="col-md-6">
                        <label class="fw-bold">Activity / Profession:</label>
                        <p id="view_activity" class="text-muted mb-0"></p>
                    </div>
                    <div class="col-md-6">
                        <label class="fw-bold">Date Received:</label>
                        <p id="view_date" class="text-muted mb-0"></p>
                    </div>
                    <div class="col-md-6">
                        <label class="fw-bold">Status:</label>
                        <div>
                            <select id="view_status_select" class="form-select form-select-sm w-50 d-inline-block">
                                <option value="pending">Pending</option>
                                <option value="read">Read</option>
                                <option value="replied">Replied</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-12">
                        <label class="fw-bold">Message Content:</label>
                        <div class="p-3 bg-light rounded border text-break" id="view_message"></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <input type="hidden" id="view_message_id">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(function() {
        var table = $('#dataTable').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            ajax: "{{ route('admin.contact-messages.index') }}",
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                {data: 'full_name', name: 'name'},
                {data: 'email', name: 'email'},
                {data: 'telephone', name: 'telephone'},
                {data: 'nation', name: 'nation'},
                {data: 'message_snippet', name: 'message'},
                {data: 'status', name: 'status', orderable: false, searchable: false},
                {data: 'created_at_formatted', name: 'created_at'},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ]
        });

        // View Message Details
        $(document).on('click', '.view-btn', function() {
            var id = $(this).data('id');
            $('#view_message_id').val(id);

            $.ajax({
                url: "{{ url('admin/contact-messages') }}/" + id,
                type: 'GET',
                success: function(response) {
                    if(response.status) {
                        var msg = response.message;
                        $('#view_name').text(msg.name || 'N/A');
                        $('#view_surname').text(msg.surname || 'N/A');
                        $('#view_email').text(msg.email || 'N/A');
                        $('#view_telephone').text(msg.telephone || 'N/A');
                        $('#view_nation').text(msg.nation || 'N/A');
                        $('#view_activity').text(msg.activity || 'N/A');
                        $('#view_date').text(response.date || 'N/A');
                        $('#view_status_select').val(msg.status);
                        $('#view_message').text(msg.message || 'No message provided.');

                        $('#viewModal').modal('show');
                        table.ajax.reload(null, false);
                    }
                },
                error: function() {
                    if (typeof toastr !== 'undefined') {
                        toastr.error('Error fetching message details.');
                    } else {
                        alert('Error fetching message details.');
                    }
                }
            });
        });

        // Change Status from Modal
        $('#view_status_select').on('change', function() {
            var id = $('#view_message_id').val();
            var status = $(this).val();

            $.ajax({
                url: "{{ url('admin/contact-messages/status') }}/" + id,
                type: 'POST',
                data: { _token: "{{ csrf_token() }}", status: status },
                success: function(response) {
                    table.ajax.reload(null, false);
                    if (typeof toastr !== 'undefined') {
                        toastr.success(response.success);
                    }
                },
                error: function() {
                    if (typeof toastr !== 'undefined') {
                        toastr.error('Error updating status.');
                    }
                }
            });
        });

        // Delete Message
        $(document).on('click', '.delete-btn', function() {
            var id = $(this).data('id');
            if(confirm('Are you sure you want to delete this contact message?')) {
                $.ajax({
                    url: "{{ url('admin/contact-messages') }}/" + id,
                    type: 'DELETE',
                    data: { _token: "{{ csrf_token() }}" },
                    success: function(response) {
                        table.ajax.reload(null, false);
                        if (typeof toastr !== 'undefined') {
                            toastr.success(response.success);
                        } else {
                            alert(response.success);
                        }
                    },
                    error: function() {
                        if (typeof toastr !== 'undefined') {
                            toastr.error('Error deleting contact message.');
                        } else {
                            alert('Error deleting contact message.');
                        }
                    }
                });
            }
        });
    });
</script>
@endpush

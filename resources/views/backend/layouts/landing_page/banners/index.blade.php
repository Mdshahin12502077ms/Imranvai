@extends('backend.app')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
            <h4 class="mb-sm-0">Banners</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Landing Page</a></li>
                    <li class="breadcrumb-item active">Banners</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header align-items-center d-flex">
                <h4 class="card-title mb-0 flex-grow-1">Banners List</h4>
                <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#addModal">Add Banner</button>
            </div>
            <div class="card-body">
                <table class="table table-bordered dt-responsive nowrap" id="dataTable">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Title</th>
                            <th>Subtitle</th>
                            <th>Banner Image</th>
                            <th>Banner Logo</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Add Modal -->
<div class="modal fade" id="addModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Banner</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addForm">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Title</label>
                        <textarea name="title" class="form-control" rows="2"></textarea>
                    </div>
                    <div class="mb-3">
                        <label>Subtitle</label>
                        <textarea name="subtitle" class="form-control" rows="2"></textarea>
                    </div>
                    <div class="mb-3">
                        <label>Banner Image</label>
                        <input type="file" name="banner_image" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label>Banner Logo</label>
                        <input type="file" name="banner_logo" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Banner</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editForm">
                @csrf
                <input type="hidden" name="id" id="edit_id">
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Title</label>
                        <textarea name="title" id="edit_title" class="form-control" rows="2"></textarea>
                    </div>
                    <div class="mb-3">
                        <label>Subtitle</label>
                        <textarea name="subtitle" id="edit_subtitle" class="form-control" rows="2"></textarea>
                    </div>
                    <div class="mb-3">
                        <label>Banner Image</label>
                        <input type="file" name="banner_image" class="form-control">
                        <img id="edit_image_preview" src="" width="100" class="mt-2" style="display:none;">
                    </div>
                    <div class="mb-3">
                        <label>Banner Logo</label>
                        <input type="file" name="banner_logo" class="form-control">
                        <img id="edit_logo_preview" src="" width="100" class="mt-2" style="display:none;">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
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
            ajax: "{{ route('admin.landing-page.banners.index') }}",
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                {data: 'title', name: 'title'},
                {data: 'subtitle', name: 'subtitle'},
                {data: 'banner_image', name: 'banner_image', orderable: false, searchable: false},
                {data: 'banner_logo', name: 'banner_logo', orderable: false, searchable: false},
                {data: 'status', name: 'status', orderable: false, searchable: false},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ]
        });

        // Add
        $('#addForm').on('submit', function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            $.ajax({
                url: "{{ route('admin.landing-page.banners.store') }}",
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    $('#addModal').modal('hide');
                    $('#addForm')[0].reset();
                    table.ajax.reload();
                    toastr.success(response.success);
                },
                error: function(xhr) {
                    toastr.error('Error adding banner');
                }
            });
        });

        // Edit
        $(document).on('click', '.edit-btn', function() {
            $('#edit_id').val($(this).data('id'));
            $('#edit_title').val($(this).data('title'));
            $('#edit_subtitle').val($(this).data('subtitle'));
            
            var image = $(this).data('image');
            if(image) {
                $('#edit_image_preview').attr('src', image).show();
            } else {
                $('#edit_image_preview').hide();
            }

            var logo = $(this).data('logo');
            if(logo) {
                $('#edit_logo_preview').attr('src', logo).show();
            } else {
                $('#edit_logo_preview').hide();
            }

            $('#editModal').modal('show');
        });

        $('#editForm').on('submit', function(e) {
            e.preventDefault();
            var id = $('#edit_id').val();
            var formData = new FormData(this);
            $.ajax({
                url: "{{ url('admin/landing-page/banners') }}/" + id,
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    $('#editModal').modal('hide');
                    table.ajax.reload();
                    toastr.success(response.success);
                },
                error: function(xhr) {
                    toastr.error('Error updating banner');
                }
            });
        });

        // Delete
        $(document).on('click', '.delete-btn', function() {
            var id = $(this).data('id');
            if(confirm('Are you sure you want to delete this?')) {
                $.ajax({
                    url: "{{ url('admin/landing-page/banners') }}/" + id,
                    type: 'DELETE',
                    data: { _token: "{{ csrf_token() }}" },
                    success: function(response) {
                        table.ajax.reload();
                        toastr.success(response.success);
                    }
                });
            }
        });

        // Status Update
        $(document).on('change', '.status-switch', function() {
            var id = $(this).data('id');
            var status = $(this).is(':checked') ? 'active' : 'inactive';
            $.ajax({
                url: "{{ url('admin/landing-page/banners/status') }}/" + id,
                type: 'POST',
                data: { _token: "{{ csrf_token() }}", status: status },
                success: function(response) {
                    toastr.success(response.success);
                }
            });
        });
    });
</script>
@endpush

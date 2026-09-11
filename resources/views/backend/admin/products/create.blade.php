@extends('backend.app')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
            <h4 class="mb-sm-0">Add Product</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.products.index') }}">Products</a></li>
                    <li class="breadcrumb-item active">Add Product</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">Product Details</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Product Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Status <span class="text-danger">*</span></label>
                            <select name="status" class="form-control" required>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label>Description</label>
                            <textarea name="description" class="form-control" rows="4"></textarea>
                        </div>
                    </div>

                    <hr>
                    <h5 class="mb-3">Product Gallery Images</h5>
                    <div id="image_container">
                        <div class="row image-row align-items-end mb-3">
                            <div class="col-md-5">
                                <label>Image</label>
                                <input type="file" name="images[]" class="form-control image-input" accept="image/*">
                            </div>
                            <div class="col-md-5">
                                <label>Preview</label><br>
                                <img src="" alt="preview" class="image-preview" style="height:80px; display:none; object-fit:cover; border-radius:5px;">
                            </div>
                            <div class="col-md-2">
                                <button type="button" class="btn btn-success add-image-btn"><i class="fa fa-plus"></i></button>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary w-md">Save Product</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Image Preview logic
        $(document).on('change', '.image-input', function() {
            var input = this;
            var preview = $(this).closest('.image-row').find('.image-preview');
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    preview.attr('src', e.target.result).show();
                }
                reader.readAsDataURL(input.files[0]);
            } else {
                preview.hide();
            }
        });

        // Add more images
        $('.add-image-btn').click(function() {
            var html = `
            <div class="row image-row align-items-end mb-3">
                <div class="col-md-5">
                    <label>Image</label>
                    <input type="file" name="images[]" class="form-control image-input" accept="image/*">
                </div>
                <div class="col-md-5">
                    <label>Preview</label><br>
                    <img src="" alt="preview" class="image-preview" style="height:80px; display:none; object-fit:cover; border-radius:5px;">
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-danger remove-image-btn"><i class="fa fa-trash"></i></button>
                </div>
            </div>`;
            $('#image_container').append(html);
        });

        $(document).on('click', '.remove-image-btn', function() {
            $(this).closest('.image-row').remove();
        });
    });
</script>
@endpush

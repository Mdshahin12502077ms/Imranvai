@extends('backend.app')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
            <h4 class="mb-sm-0">Edit Product</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.products.index') }}">Products</a></li>
                    <li class="breadcrumb-item active">Edit Product</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">Edit Product Details</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Product Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" value="{{ $product->name }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Status <span class="text-danger">*</span></label>
                            <select name="status" class="form-control" required>
                                <option value="active" {{ $product->status == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ $product->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label>Description</label>
                            <textarea name="description" class="form-control" rows="4">{{ $product->description }}</textarea>
                        </div>
                    </div>

                    <hr>
                    <h5 class="mb-3">Existing Gallery Images</h5>
                    <div class="row mb-4">
                        @foreach($product->images as $img)
                        <div class="col-md-3 text-center mb-3" id="existing_img_{{ $img->id }}">
                            <img src="{{ asset($img->image) }}" style="height:100px; width:100%; object-fit:cover; border-radius:5px; margin-bottom:10px;">
                            <button type="button" class="btn btn-danger btn-sm delete-existing-image" data-id="{{ $img->id }}">
                                <i class="fa fa-trash"></i> Delete Image
                            </button>
                        </div>
                        @endforeach
                    </div>

                    <h5 class="mb-3">Add New Gallery Images</h5>
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
                        <button type="submit" class="btn btn-primary w-md">Update Product</button>
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

        // Delete existing image via AJAX
        $(document).on('click', '.delete-existing-image', function() {
            var id = $(this).data('id');
            var btn = $(this);
            if(confirm('Are you sure you want to delete this image immediately?')) {
                $.ajax({
                    url: "{{ url('admin/product-images') }}/" + id,
                    type: 'DELETE',
                    data: { _token: "{{ csrf_token() }}" },
                    success: function(response) {
                        $('#existing_img_' + id).fadeOut(300, function() { $(this).remove(); });
                        toastr.success(response.success);
                    },
                    error: function(xhr) {
                        toastr.error('Error deleting image');
                    }
                });
            }
        });
    });
</script>
@endpush

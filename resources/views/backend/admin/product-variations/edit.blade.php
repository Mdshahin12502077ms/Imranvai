@extends('backend.app')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
            <h4 class="mb-sm-0">Edit Product Variation</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.product-variations.index') }}">Product Variations</a></li>
                    <li class="breadcrumb-item active">Edit Variation</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">Edit Variation Details</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.product-variations.update', $variation->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Product <span class="text-danger">*</span></label>
                            <select name="product_id" class="form-control" required>
                                <option value="">Select Product</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}" {{ $variation->product_id == $product->id ? 'selected' : '' }}>{{ $product->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Status <span class="text-danger">*</span></label>
                            <select name="status" class="form-control" required>
                                <option value="active" {{ $variation->status == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ $variation->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Price</label>
                            <input type="number" step="0.01" name="price" class="form-control" value="{{ $variation->price }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Length</label>
                            <input type="number" step="0.01" name="length" class="form-control" value="{{ $variation->length }}">
                        </div>
                    </div>

                    <hr>
                    <h5 class="mb-3">Existing Technical Specifications</h5>
                    @foreach($variation->technicalSpecifications as $spec)
                    <div class="row align-items-end mb-3" id="existing_spec_{{ $spec->id }}">
                        <div class="col-md-5">
                            <label>Title</label>
                            <input type="text" class="form-control" value="{{ $spec->title }}" readonly>
                        </div>
                        <div class="col-md-5">
                            <label>Specification Details</label>
                            <input type="text" class="form-control" value="{{ $spec->specification }}" readonly>
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="btn btn-danger delete-existing-spec" data-id="{{ $spec->id }}">
                                <i class="fa fa-trash"></i> Delete
                            </button>
                        </div>
                    </div>
                    @endforeach

                    <h5 class="mb-3 mt-4">Add New Technical Specifications</h5>
                    <div id="spec_container">
                        <div class="row spec-row align-items-end mb-3">
                            <div class="col-md-5">
                                <label>Title</label>
                                <input type="text" name="specs_title[]" class="form-control" placeholder="e.g. Battery Capacity">
                            </div>
                            <div class="col-md-5">
                                <label>Specification Details</label>
                                <input type="text" name="specs_desc[]" class="form-control" placeholder="e.g. 5000 mAh">
                            </div>
                            <div class="col-md-2">
                                <button type="button" class="btn btn-success add-spec-btn"><i class="fa fa-plus"></i> Add More</button>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary w-md">Update Variation</button>
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
        // Add more tech specs
        $('.add-spec-btn').click(function() {
            var html = `
            <div class="row spec-row align-items-end mb-3">
                <div class="col-md-5">
                    <label>Title</label>
                    <input type="text" name="specs_title[]" class="form-control" placeholder="e.g. Range">
                </div>
                <div class="col-md-5">
                    <label>Specification Details</label>
                    <input type="text" name="specs_desc[]" class="form-control" placeholder="e.g. 400 km">
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-danger remove-spec-btn"><i class="fa fa-trash"></i> Remove</button>
                </div>
            </div>`;
            $('#spec_container').append(html);
        });

        $(document).on('click', '.remove-spec-btn', function() {
            $(this).closest('.spec-row').remove();
        });

        // Delete existing spec via AJAX
        $(document).on('click', '.delete-existing-spec', function() {
            var id = $(this).data('id');
            if(confirm('Are you sure you want to delete this specification immediately?')) {
                $.ajax({
                    url: "{{ url('admin/product-variations/spec') }}/" + id,
                    type: 'DELETE',
                    data: { _token: "{{ csrf_token() }}" },
                    success: function(response) {
                        $('#existing_spec_' + id).fadeOut(300, function() { $(this).remove(); });
                        toastr.success(response.success);
                    },
                    error: function(xhr) {
                        toastr.error('Error deleting specification');
                    }
                });
            }
        });
    });
</script>
@endpush

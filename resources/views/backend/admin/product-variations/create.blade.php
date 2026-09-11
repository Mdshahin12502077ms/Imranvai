@extends('backend.app')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
            <h4 class="mb-sm-0">Add Product Variation</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.product-variations.index') }}">Product Variations</a></li>
                    <li class="breadcrumb-item active">Add Variation</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">Variation Details</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.product-variations.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Product <span class="text-danger">*</span></label>
                            <select name="product_id" class="form-control" required>
                                <option value="">Select Product</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}">{{ $product->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Status <span class="text-danger">*</span></label>
                            <select name="status" class="form-control" required>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Price</label>
                            <input type="number" step="0.01" name="price" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Length</label>
                            <input type="number" step="0.01" name="length" class="form-control">
                        </div>
                    </div>

                    <hr>
                    <h5 class="mb-3">Technical Specifications</h5>
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
                        <button type="submit" class="btn btn-primary w-md">Save Variation</button>
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
    });
</script>
@endpush

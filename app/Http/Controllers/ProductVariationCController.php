<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProductVariation;
use App\Models\Product;
use App\Models\TechnicalSpecification;
use Yajra\DataTables\Facades\DataTables;

class ProductVariationCController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = ProductVariation::with('product')->latest()->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('product_name', function ($row) {
                    return $row->product->name ?? 'N/A';
                })
                ->addColumn('status', function ($row) {
                    if ($row->status == 'active') {
                        return '<span class="badge badge-success bg-success">Active</span>';
                    } else {
                        return '<span class="badge badge-danger bg-danger">Inactive</span>';
                    }
                })
                ->addColumn('action', function ($row) {
                    $btn = '<a href="'.route('admin.product-variations.edit', $row->id).'" class="edit btn btn-primary btn-sm"><i class="fa fa-edit"></i></a> ';
                    $btn .= '<form action="'.route('admin.product-variations.destroy', $row->id).'" method="POST" style="display:inline-block;">
                                ' . csrf_field() . method_field('DELETE') . '
                                <button type="submit" class="btn btn-sm btn-danger delete-btn" onclick="return confirm(\'Are you sure you want to delete this variation?\')"><i class="fa fa-trash"></i></button>
                            </form>';
                    return $btn;
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }
        return view('backend.admin.product-variations.index');
    }

    public function create()
    {
        $products = Product::all();
        return view('backend.admin.product-variations.create', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'price' => 'nullable|numeric',
            'length' => 'nullable|numeric',
            'status' => 'required|in:active,inactive',
            'specs_title.*' => 'nullable|string',
            'specs_desc.*' => 'nullable|string',
        ]);

        $variation = ProductVariation::create([
            'product_id' => $request->product_id,
            'price' => $request->price,
            'length' => $request->length,
            'status' => $request->status,
        ]);

        // Save Tech Specs
        if ($request->has('specs_title')) {
            foreach ($request->specs_title as $index => $title) {
                if ($title || isset($request->specs_desc[$index])) {
                    TechnicalSpecification::create([
                        'product_id' => $request->product_id,
                        'product_variation_id' => $variation->id,
                        'title' => $title,
                        'specification' => $request->specs_desc[$index] ?? '',
                        'status' => 'active'
                    ]);
                }
            }
        }

        return redirect()->route('admin.product-variations.index')->with('success', 'Variation created successfully');
    }

    public function edit($id)
    {
        $variation = ProductVariation::with('technicalSpecifications')->findOrFail($id);
        $products = Product::all();
        return view('backend.admin.product-variations.edit', compact('variation', 'products'));
    }

    public function update(Request $request, $id)
    {
        $variation = ProductVariation::findOrFail($id);

        $request->validate([
            'product_id' => 'required|exists:products,id',
            'price' => 'nullable|numeric',
            'length' => 'nullable|numeric',
            'status' => 'required|in:active,inactive',
            'specs_title.*' => 'nullable|string',
            'specs_desc.*' => 'nullable|string',
        ]);

        $variation->update([
            'product_id' => $request->product_id,
            'price' => $request->price,
            'length' => $request->length,
            'status' => $request->status,
        ]);

        // Add new Tech Specs
        if ($request->has('specs_title')) {
            foreach ($request->specs_title as $index => $title) {
                if ($title || isset($request->specs_desc[$index])) {
                    TechnicalSpecification::create([
                        'product_id' => $request->product_id,
                        'product_variation_id' => $variation->id,
                        'title' => $title,
                        'specification' => $request->specs_desc[$index] ?? '',
                        'status' => 'active'
                    ]);
                }
            }
        }

        return redirect()->route('admin.product-variations.index')->with('success', 'Variation updated successfully');
    }

    public function destroy($id)
    {
        $variation = ProductVariation::findOrFail($id);
        $variation->delete();
        return redirect()->back()->with('success', 'Variation deleted successfully');
    }

    public function destroySpec($id)
    {
        $spec = TechnicalSpecification::findOrFail($id);
        $spec->delete();
        return response()->json(['success' => 'Technical specification deleted successfully']);
    }

    public function statusUpdate(Request $request, $id)
    {
        $variation = ProductVariation::findOrFail($id);
        $variation->status = $request->status;
        $variation->save();
        return response()->json(['success' => 'Status updated successfully']);
    }
}

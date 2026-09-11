<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Product;
class ProductController extends Controller
{
    public function index(Request $request){
        if($request->ajax()){
            $data=Product::with('images')->latest()->get();
            return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('name',function($row){
              return $row->name??'';
            })
            ->addColumn('description',function($row){
              return $row->description??'';
            })
            ->addColumn('image',function($row){
              $imgs=[];
              foreach($row->images as $image){
                $imgs[]='<img src="'.asset($image->image).'" height="50">';
              }
              return implode(' ',$imgs);
            })
            ->addColumn('status',function($row){
                if($row->status == 'active'){
                    return '<span class="badge badge-success">Active</span>';
                }else{
                    return '<span class="badge badge-danger">Inactive</span>';
                }
            })
            ->addColumn('action',function($row){
                $btn = '<a href="'.route('admin.products.edit', $row->id).'" class="edit btn btn-primary btn-sm"><i class="fa fa-edit"></i></a>';
                $btn .= '<a href="'.route('admin.products.destroy', $row->id).'" class="delete btn btn-danger btn-sm"><i class="fa fa-trash"></i></a>';
                return $btn;
            })
            ->rawColumns(['image','action'])
            ->make(true);

        }

        return view('backend.admin.products.index');

    }
    public function create()
    {
        return view('backend.admin.products.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
            'images.*' => 'nullable|image|max:10240',
        ]);

        $product = Product::create([
            'name' => $request->name,
            'description' => $request->description,
            'status' => $request->status,
        ]);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                // Using standard Laravel storage or simple move for the images
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/products'), $filename);
                
                \App\Models\ProductGallery::create([
                    'product_id' => $product->id,
                    'image' => 'uploads/products/' . $filename,
                    'status' => 'active'
                ]);
            }
        }

        return redirect()->route('admin.products.index')->with('success', 'Product created successfully');
    }

    public function edit($id)
    {
        $product = Product::with('images')->findOrFail($id);
        return view('backend.admin.products.edit', compact('product'));
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
            'images.*' => 'nullable|image|max:10240',
        ]);

        $product->update([
            'name' => $request->name,
            'description' => $request->description,
            'status' => $request->status,
        ]);

        // Handle new images upload
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/products'), $filename);
                
                \App\Models\ProductGallery::create([
                    'product_id' => $product->id,
                    'image' => 'uploads/products/' . $filename,
                    'status' => 'active'
                ]);
            }
        }

        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully');
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        
        foreach ($product->images as $img) {
            if ($img->image && file_exists(public_path($img->image))) {
                unlink(public_path($img->image));
            }
        }

        $product->delete();
        return redirect()->back()->with('success', 'Product deleted successfully');
    }

    public function destroyImage($id)
    {
        $img = \App\Models\ProductGallery::findOrFail($id);
        if ($img->image && file_exists(public_path($img->image))) {
            unlink(public_path($img->image));
        }
        $img->delete();
        return response()->json(['success' => 'Image deleted successfully']);
    }
}

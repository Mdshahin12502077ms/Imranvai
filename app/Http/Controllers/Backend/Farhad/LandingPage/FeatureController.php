<?php

namespace App\Http\Controllers\Backend\Farhad\LandingPage;

use App\Models\Feature;
use App\Helpers\MiaHelper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class FeatureController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Feature::latest()->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('image', function ($row) {
                    return $row->image ? '<img src="' . asset($row->image) . '" height="50">' : 'No Image';
                })
                ->addColumn('status', function ($row) {
                    $checked = $row->status == 'active' ? 'checked' : '';
                    return '<div class="form-check form-switch form-switch-right form-switch-md">
                        <input class="form-check-input status-switch" type="checkbox" data-id="' . $row->id . '" data-type="feature" ' . $checked . '>
                    </div>';
                })
                ->addColumn('action', function ($row) {
                    return '<button class="btn btn-sm btn-primary edit-btn me-1" data-id="'.$row->id.'" data-title="'.htmlspecialchars($row->title).'" data-description="'.htmlspecialchars($row->description).'" data-image="'.($row->image ? asset($row->image) : '').'" data-status="'.$row->status.'"><i class="fa-regular fa-pen-to-square"></i></button>
                            <button class="btn btn-sm btn-danger delete-btn" data-id="'.$row->id.'"><i class="fa-regular fa-trash-can"></i></button>';
                })
                ->rawColumns(['image', 'status', 'action'])
                ->make(true);
        }
        return view('backend.layouts.landing_page.features.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'nullable|string',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:10240',
        ]);

        $feature = new Feature();
        $feature->title = $request->title;
        $feature->description = $request->description;
        $feature->status = 'active';

        if ($request->hasFile('image')) {
            $feature->image = MiaHelper::uploadFile($request->file('image'), 'landing-page/features');
        }

        $feature->save();

        return response()->json(['success' => 'Feature added successfully']);
    }

    public function update(Request $request, $id)
    {
        $feature = Feature::findOrFail($id);
        
        $request->validate([
            'title' => 'nullable|string',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:10240',
        ]);

        $feature->title = $request->title;
        $feature->description = $request->description;

        if ($request->hasFile('image')) {
            $feature->image = MiaHelper::updateFile($feature->image, $request->file('image'), 'landing-page/features');
        }

        $feature->save();

        return response()->json(['success' => 'Feature updated successfully']);
    }

    public function destroy($id)
    {
        $feature = Feature::findOrFail($id);
        if ($feature->image && file_exists(public_path($feature->image))) {
            unlink(public_path($feature->image));
        }
        $feature->delete();
        return response()->json(['success' => 'Feature deleted successfully']);
    }

    public function statusUpdate(Request $request, $id)
    {
        $feature = Feature::findOrFail($id);
        $feature->status = $request->status;
        $feature->save();
        return response()->json(['success' => 'Status updated successfully']);
    }
}

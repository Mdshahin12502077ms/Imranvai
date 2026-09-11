<?php

namespace App\Http\Controllers\Backend\Farhad;

use App\Helpers\MiaHelper;
use App\Http\Controllers\Controller;
use App\Models\EveryconditionTerm;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class EveryconditionTermController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = EveryconditionTerm::latest()->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('image', function ($row) {
                    return $row->image ? '<img src="' . asset($row->image) . '" height="50" class="rounded">' : '<span class="badge bg-secondary">No Image</span>';
                })
                ->addColumn('status', function ($row) {
                    $checked = $row->status == 'active' ? 'checked' : '';
                    return '<div class="form-check form-switch form-switch-right form-switch-md">
                        <input class="form-check-input status-switch" type="checkbox" data-id="' . $row->id . '" ' . $checked . '>
                    </div>';
                })
                ->addColumn('action', function ($row) {
                    return '<button class="btn btn-sm btn-primary edit-btn me-1" 
                                data-id="' . $row->id . '" 
                                data-title="' . htmlspecialchars($row->title ?? '', ENT_QUOTES) . '" 
                                data-subtitle="' . htmlspecialchars($row->subtitle ?? '', ENT_QUOTES) . '" 
                                data-description="' . htmlspecialchars($row->description ?? '', ENT_QUOTES) . '" 
                                data-image="' . ($row->image ? asset($row->image) : '') . '" 
                                data-status="' . $row->status . '">
                                <i class="fa-regular fa-pen-to-square"></i>
                            </button>
                            <button class="btn btn-sm btn-danger delete-btn" data-id="' . $row->id . '">
                                <i class="fa-regular fa-trash-can"></i>
                            </button>';
                })
                ->rawColumns(['image', 'status', 'action'])
                ->make(true);
        }

        return view('backend.layouts.everycondition_terms.index');
    }

    public function create()
    {
        return view('backend.layouts.everycondition_terms.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,jpg,png,webp,gif|max:10240',
        ]);

        $term = new EveryconditionTerm();
        $term->title = $request->title;
        $term->subtitle = $request->subtitle;
        $term->description = $request->description;
        $term->status = 'active';

        if ($request->hasFile('image')) {
            $term->image = MiaHelper::uploadFile($request->file('image'), 'everycondition-terms');
        }

        $term->save();

        if ($request->ajax()) {
            return response()->json(['success' => 'Product Condition added successfully']);
        }

        return redirect()->route('admin.productCondition.index')->with('success', 'Product Condition added successfully');
    }

    public function edit($id)
    {
        $term = EveryconditionTerm::findOrFail($id);
        if (request()->ajax()) {
            return response()->json($term);
        }
        return view('backend.layouts.everycondition_terms.index', compact('term'));
    }

    public function update(Request $request, $id)
    {
        $term = EveryconditionTerm::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,jpg,png,webp,gif|max:10240',
        ]);

        $term->title = $request->title;
        $term->subtitle = $request->subtitle;
        $term->description = $request->description;

        if ($request->hasFile('image')) {
            $term->image = MiaHelper::updateFile($term->image, $request->file('image'), 'everycondition-terms');
        }

        $term->save();

        if ($request->ajax()) {
            return response()->json(['success' => 'Product Condition updated successfully']);
        }

        return redirect()->route('admin.productCondition.index')->with('success', 'Product Condition updated successfully');
    }

    public function destroy($id)
    {
        $term = EveryconditionTerm::findOrFail($id);
        if ($term->image) {
            MiaHelper::deleteFile($term->image);
        }
        $term->delete();

        if (request()->ajax()) {
            return response()->json(['success' => 'Product Condition deleted successfully']);
        }

        return redirect()->back()->with('success', 'Product Condition deleted successfully.');
    }

    public function statusUpdate(Request $request, $id)
    {
        $term = EveryconditionTerm::findOrFail($id);
        $term->status = $request->status ?? ($term->status === 'active' ? 'inactive' : 'active');
        $term->save();

        return response()->json(['success' => 'Status updated successfully']);
    }
}

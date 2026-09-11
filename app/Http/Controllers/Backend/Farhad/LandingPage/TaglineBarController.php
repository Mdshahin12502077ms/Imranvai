<?php

namespace App\Http\Controllers\Backend\Farhad\LandingPage;

use App\Models\TaglineBar;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class TaglineBarController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = TaglineBar::latest()->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('status', function ($row) {
                    $checked = $row->status == 'active' ? 'checked' : '';
                    return '<div class="form-check form-switch form-switch-right form-switch-md">
                        <input class="form-check-input status-switch" type="checkbox" data-id="' . $row->id . '" data-type="tagline_bar" ' . $checked . '>
                    </div>';
                })
                ->addColumn('action', function ($row) {
                    return '<button class="btn btn-sm btn-primary edit-btn me-1" data-id="'.$row->id.'" data-title="'.htmlspecialchars($row->title).'" data-sub_title="'.htmlspecialchars($row->sub_title).'" data-status="'.$row->status.'"><i class="fa-regular fa-pen-to-square"></i></button>
                            <button class="btn btn-sm btn-danger delete-btn" data-id="'.$row->id.'"><i class="fa-regular fa-trash-can"></i></button>';
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }
        return view('backend.layouts.landing_page.taglines.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'nullable|string',
            'sub_title' => 'nullable|string',
        ]);

        $tagline = new TaglineBar();
        $tagline->title = $request->title;
        $tagline->sub_title = $request->sub_title;
        $tagline->status = 'active';
        $tagline->save();

        return response()->json(['success' => 'Tagline added successfully']);
    }

    public function update(Request $request, $id)
    {
        $tagline = TaglineBar::findOrFail($id);
        
        $request->validate([
            'title' => 'nullable|string',
            'sub_title' => 'nullable|string',
        ]);

        $tagline->title = $request->title;
        $tagline->sub_title = $request->sub_title;
        $tagline->save();

        return response()->json(['success' => 'Tagline updated successfully']);
    }

    public function destroy($id)
    {
        $tagline = TaglineBar::findOrFail($id);
        $tagline->delete();
        return response()->json(['success' => 'Tagline deleted successfully']);
    }

    public function statusUpdate(Request $request, $id)
    {
        $tagline = TaglineBar::findOrFail($id);
        $tagline->status = $request->status;
        $tagline->save();
        return response()->json(['success' => 'Status updated successfully']);
    }
}

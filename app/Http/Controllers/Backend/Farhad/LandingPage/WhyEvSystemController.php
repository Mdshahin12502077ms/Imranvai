<?php

namespace App\Http\Controllers\Backend\Farhad\LandingPage;

use App\Models\WhyEvSystem;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class WhyEvSystemController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = WhyEvSystem::latest()->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('status', function ($row) {
                    $checked = $row->status == 'active' ? 'checked' : '';
                    return '<div class="form-check form-switch form-switch-right form-switch-md">
                        <input class="form-check-input status-switch" type="checkbox" data-id="' . $row->id . '" data-type="why_ev" ' . $checked . '>
                    </div>';
                })
                ->addColumn('action', function ($row) {
                    return '<button class="btn btn-sm btn-primary edit-btn me-1" data-id="'.$row->id.'" data-title="'.htmlspecialchars($row->title).'" data-description="'.htmlspecialchars($row->description).'" data-status="'.$row->status.'"><i class="fa-regular fa-pen-to-square"></i></button>
                            <button class="btn btn-sm btn-danger delete-btn" data-id="'.$row->id.'"><i class="fa-regular fa-trash-can"></i></button>';
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }
        return view('backend.layouts.landing_page.why_ev_systems.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        $item = new WhyEvSystem();
        $item->title = $request->title;
        $item->description = $request->description;
        $item->status = 'active';
        $item->save();

        return response()->json(['success' => 'System added successfully']);
    }

    public function update(Request $request, $id)
    {
        $item = WhyEvSystem::findOrFail($id);
        
        $request->validate([
            'title' => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        $item->title = $request->title;
        $item->description = $request->description;
        $item->save();

        return response()->json(['success' => 'System updated successfully']);
    }

    public function destroy($id)
    {
        $item = WhyEvSystem::findOrFail($id);
        $item->delete();
        return response()->json(['success' => 'System deleted successfully']);
    }

    public function statusUpdate(Request $request, $id)
    {
        $item = WhyEvSystem::findOrFail($id);
        $item->status = $request->status;
        $item->save();
        return response()->json(['success' => 'Status updated successfully']);
    }
}

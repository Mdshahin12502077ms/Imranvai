<?php

namespace App\Http\Controllers\Backend\Farhad\LandingPage;

use App\Models\Banner;
use App\Helpers\MiaHelper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class BannerController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Banner::latest()->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('banner_image', function ($row) {
                    return $row->banner_image ? '<img src="' . asset($row->banner_image) . '" height="50">' : 'No Image';
                })
                ->addColumn('banner_logo', function ($row) {
                    return $row->banner_logo ? '<img src="' . asset($row->banner_logo) . '" height="50">' : 'No Image';
                })
                ->addColumn('status', function ($row) {
                    $checked = $row->status == 'active' ? 'checked' : '';
                    return '<div class="form-check form-switch form-switch-right form-switch-md">
                        <input class="form-check-input status-switch" type="checkbox" data-id="' . $row->id . '" data-type="banner" ' . $checked . '>
                    </div>';
                })
                ->addColumn('action', function ($row) {
                    return '<button class="btn btn-sm btn-primary edit-btn me-1" data-id="'.$row->id.'" data-title="'.htmlspecialchars($row->title).'" data-subtitle="'.htmlspecialchars($row->subtitle).'" data-image="'.($row->banner_image ? asset($row->banner_image) : '').'" data-logo="'.($row->banner_logo ? asset($row->banner_logo) : '').'" data-status="'.$row->status.'"><i class="fa-regular fa-pen-to-square"></i></button>
                            <button class="btn btn-sm btn-danger delete-btn" data-id="'.$row->id.'"><i class="fa-regular fa-trash-can"></i></button>';
                })
                ->rawColumns(['banner_image', 'banner_logo', 'status', 'action'])
                ->make(true);
        }
        return view('backend.layouts.landing_page.banners.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'nullable|string',
            'subtitle' => 'nullable|string',
            'banner_image' => 'nullable|image|max:10240',
            'banner_logo' => 'nullable|image|max:10240',
        ]);

        $banner = new Banner();
        $banner->title = $request->title;
        $banner->subtitle = $request->subtitle;
        $banner->status = 'active';

        if ($request->hasFile('banner_image')) {
            $banner->banner_image = MiaHelper::uploadFile($request->file('banner_image'), 'landing-page/banners');
        }
        if ($request->hasFile('banner_logo')) {
            $banner->banner_logo = MiaHelper::uploadFile($request->file('banner_logo'), 'landing-page/banners');
        }

        $banner->save();

        return response()->json(['success' => 'Banner added successfully']);
    }

    public function update(Request $request, $id)
    {
        $banner = Banner::findOrFail($id);
        
        $request->validate([
            'title' => 'nullable|string',
            'subtitle' => 'nullable|string',
            'banner_image' => 'nullable|image|max:10240',
            'banner_logo' => 'nullable|image|max:10240',
        ]);

        $banner->title = $request->title;
        $banner->subtitle = $request->subtitle;

        if ($request->hasFile('banner_image')) {
            $banner->banner_image = MiaHelper::updateFile($banner->banner_image, $request->file('banner_image'), 'landing-page/banners');
        }
        if ($request->hasFile('banner_logo')) {
            $banner->banner_logo = MiaHelper::updateFile($banner->banner_logo, $request->file('banner_logo'), 'landing-page/banners');
        }

        $banner->save();

        return response()->json(['success' => 'Banner updated successfully']);
    }

    public function destroy($id)
    {
        $banner = Banner::findOrFail($id);
        if ($banner->banner_image && file_exists(public_path($banner->banner_image))) {
            unlink(public_path($banner->banner_image));
        }
        if ($banner->banner_logo && file_exists(public_path($banner->banner_logo))) {
            unlink(public_path($banner->banner_logo));
        }
        $banner->delete();
        return response()->json(['success' => 'Banner deleted successfully']);
    }

    public function statusUpdate(Request $request, $id)
    {
        $banner = Banner::findOrFail($id);
        $banner->status = $request->status; // 'active' or 'inactive'
        $banner->save();
        return response()->json(['success' => 'Status updated successfully']);
    }
}

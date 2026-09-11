<?php

namespace App\Http\Controllers\Backend\Farhad;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ContactMessageController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = ContactMessage::latest()->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('full_name', function ($row) {
                    return e(trim(($row->name ?? '') . ' ' . ($row->surname ?? '')));
                })
                ->addColumn('status', function ($row) {
                    $badgeClass = 'bg-warning';
                    if ($row->status == 'read') {
                        $badgeClass = 'bg-info';
                    } elseif ($row->status == 'replied') {
                        $badgeClass = 'bg-success';
                    }
                    return '<span class="badge ' . $badgeClass . ' text-uppercase">' . e($row->status) . '</span>';
                })
                ->addColumn('message_snippet', function ($row) {
                    $msg = $row->message ?? '';
                    return e(mb_strimwidth($msg, 0, 50, '...'));
                })
                ->addColumn('created_at_formatted', function ($row) {
                    return $row->created_at ? $row->created_at->format('d M Y, h:i A') : '';
                })
                ->addColumn('action', function ($row) {
                    return '<button class="btn btn-sm btn-info view-btn me-1" data-id="' . $row->id . '">
                                <i class="fa-regular fa-eye"></i> View
                            </button>
                            <button class="btn btn-sm btn-danger delete-btn" data-id="' . $row->id . '">
                                <i class="fa-regular fa-trash-can"></i>
                            </button>';
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }

        return view('backend.layouts.contact_messages.index');
    }

    public function show($id)
    {
        $message = ContactMessage::findOrFail($id);

        if ($message->status == 'pending') {
            $message->status = 'read';
            $message->save();
        }

        return response()->json([
            'status'  => true,
            'message' => $message,
            'date'    => $message->created_at ? $message->created_at->format('d M Y, h:i A') : '',
        ]);
    }

    public function destroy($id)
    {
        $message = ContactMessage::findOrFail($id);
        $message->delete();

        if (request()->ajax()) {
            return response()->json(['success' => 'Contact message deleted successfully.']);
        }

        return redirect()->back()->with('success', 'Contact message deleted successfully.');
    }

    public function statusUpdate(Request $request, $id)
    {
        $message = ContactMessage::findOrFail($id);
        $request->validate([
            'status' => 'required|in:pending,read,replied',
        ]);

        $message->status = $request->status;
        $message->save();

        return response()->json(['success' => 'Message status updated successfully.']);
    }
}

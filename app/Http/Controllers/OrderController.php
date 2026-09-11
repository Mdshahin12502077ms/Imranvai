<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $orders = Order::with(['customer', 'customerInfo', 'product', 'productVariation'])->latest();
            return DataTables::of($orders)
                ->addIndexColumn()
                ->addColumn('order_status', function ($order) {
                    $statuses = ['pending', 'processing', 'completed', 'cancelled'];
                    $options = '';
                    foreach ($statuses as $status) {
                        $selected = $order->order_status == $status ? 'selected' : '';
                        $options .= '<option value="' . $status . '" ' . $selected . '>' . ucfirst($status) . '</option>';
                    }

                    $badgeClass = match ($order->order_status) {
                        'completed'  => 'text-success font-weight-bold',
                        'processing' => 'text-info font-weight-bold',
                        'cancelled'  => 'text-danger font-weight-bold',
                        default      => 'text-warning font-weight-bold',
                    };

                    return '<select class="form-select form-select-sm order-status-select ' . $badgeClass . '" data-id="' . $order->id . '" style="width: 130px;">' . $options . '</select>';
                })
                ->addColumn('product_and_variation_info', function ($order) {
                    $info = [];
                    if ($order->product) {
                        $info[] = $order->product->title;
                    }
                    if ($order->productVariation && $order->productVariation->length) {
                        $info[] = '(' . $order->productVariation->length . ')';
                    }
                    return !empty($info) ? implode(' ', $info) : 'N/A';
                })
                ->addColumn('payment_status', function ($order) {
                    $badgeClass = match ($order->payment_status) {
                        'paid'   => 'bg-success',
                        'failed' => 'bg-danger',
                        default  => 'bg-warning',
                    };
                    return '<span class="badge ' . $badgeClass . '">' . ucfirst($order->payment_status) . '</span>';
                })
                ->addColumn('created_at', function ($order) {
                    return $order->created_at ? $order->created_at->format('d M, Y') : 'N/A';
                })
                ->addColumn('customer_id', function ($order) {
                    if ($order->customer) {
                        return $order->customer->name;
                    }
                    if ($order->customerInfo) {
                        $name = trim($order->customerInfo->first_name . ' ' . $order->customerInfo->last_name);
                        return $name . ($order->customerInfo->email ? ' (' . $order->customerInfo->email . ')' : '');
                    }
                    return 'N/A';
                })
                ->addColumn('price', function ($order) {
                    return '$' . number_format($order->grand_total, 2);
                })
                ->addColumn('actions', function ($order) {
                    return '
                        <a href="' . route('admin.orders.show', $order->id) . '" class="btn btn-sm btn-info me-1"><i class="ri-eye-line align-middle"></i> View</a>
                        <button type="button" class="btn btn-sm btn-danger delete-order-btn" data-id="' . $order->id . '"><i class="ri-delete-bin-line align-middle"></i> Delete</button>
                    ';
                })
                ->rawColumns(['actions', 'order_status', 'payment_status'])
                ->make(true);
        }

        return view('backend.layouts.orders.index');
    }

    public function show($id)
    {
        $order = Order::with(['customer', 'customerInfo', 'product', 'productVariation'])->findOrFail($id);
        return view('backend.layouts.orders.show', compact('order'));
    }

    public function statusUpdate(Request $request, $id)
    {
        $request->validate([
            'order_status' => 'required|in:pending,processing,completed,cancelled'
        ]);

        $order = Order::findOrFail($id);
        $order->update([
            'order_status' => $request->order_status
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Order status updated successfully!'
        ]);
    }

    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        $order->delete();

        return response()->json([
            'success' => true,
            'message' => 'Order deleted successfully!'
        ]);
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MyorderController extends Controller
{
    public function index(){
        try{
             $order=Order::with(['product','productVariation','customer','customerInfo','product.images','productVariation.technicalSpecifications'])
             ->where('customer_id',Auth::guard('api')->user()->id)->get()->map(function($order){

                $order->product->images = $order->product->images->map(function($image){
                    return asset($image->image);
                });
                return $order;
             });
             return response()->json([
                'status' => 'success',
                'message' => 'Orders fetched successfully.',
                'data' => $order
             ]);
        }
        catch(Exception $ex){
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while fetching orders.',
                'error' => $ex->getMessage()
            ], 500);
        }
    }

    public function show($order){
        try{
            $order=Order::with(['product','productVariation','customer','customerInfo','product.images','productVariation.technicalSpecifications'])
            ->where('customer_id',Auth::guard('api')->user()->id)->find($order);
            return response()->json([
                'status' => 'success',
                'message' => 'Order fetched successfully.',
                'data' => $order
            ]);
        }
        catch(Exception $ex){
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while fetching order.',
                'error' => $ex->getMessage()
            ], 500);
        }
    }
}

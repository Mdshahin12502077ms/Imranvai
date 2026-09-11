<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductVariation;
use Exception;
use Illuminate\Http\Request;

class FrontendProductController extends Controller
{
    public function index()
    {
        try {
            $products = Product::with('images', 'variations.technicalSpecifications')->where('status','active')->latest()->get();

            $data = $products->map(function($product) {
                return [
                     "product_title" => $product->name, // DB uses 'name' instead of 'title'
                     "product_description" => $product->description,
                     "gallery_image" => $product->images->map(function($image){
                        return [
                            'id' => $image->id,
                            'image' => asset($image->image),
                        ];
                     }),
                     "product_variation" => $product->variations->map(function($variation){
                        return [
                            'id' => $variation->id,
                            'price' => $variation->price,
                            'length' => $variation->length,
                            "technical_specification" => $variation->technicalSpecifications->map(function($specification){
                                return [
                                    'id' => $specification->id,
                                    'specification_name' => $specification->title, // DB uses 'title'
                                    'specification_value' => $specification->specification, // DB uses 'specification'
                                ];
                            }),
                        ];
                     })
                ];
            });

            return response()->json([
                'status'=>'success',
                'message'=>'Product fetched successfully',
                'data'=>$data
            ],200);
        }
        catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    public function show($id)
    {
        try {
            $product = Product::with('images', 'variations.technicalSpecifications')->where('status','active')->find($id);
            if(!$product){
                return response()->json([
                    'status'=>'error',
                    'message'=>'Product not found',
                ],404);
            }

            $data = [
                     "product_title" => $product->name,
                     "product_description" => $product->description,
                     "gallery_image" => $product->images->map(function($image){
                        return [
                            'id' => $image->id,
                            'image' => asset($image->image),
                        ];
                     }),
                     "product_variation" => $product->variations->map(function($variation){
                        return [
                            'id' => $variation->id,
                            'price' => $variation->price,
                            'length' => $variation->length,
                            "technical_specification" => $variation->technicalSpecifications->map(function($specification){
                                return [
                                    'id' => $specification->id,
                                    'specification_name' => $specification->title,
                                    'specification_value' => $specification->specification,
                                ];
                            }),
                        ];
                     })
                ];

            return response()->json([
                'status'=>'success',
                'message'=>'Product fetched successfully',
                'data'=>$data
            ],200);
        }
        catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function getVariation($id){
        try {
            $variation = ProductVariation::with('technicalSpecifications')->where('status','active')->find($id);
            if(!$variation){
                return response()->json([
                    'status'=>'error',
                    'message'=>'Variation not found',
                ],404);
            }

            $data = [
                     'id' => $variation->id,
                     'price' => $variation->price,
                     'length' => $variation->length,
                     "technical_specification" => $variation->technicalSpecifications->map(function($specification){
                        return [
                            'id' => $specification->id,
                            'specification_name' => $specification->title,
                            'specification_value' => $specification->specification,
                        ];
                     })
                ];

            return response()->json([
                'status'=>'success',
                'message'=>'Variation fetched successfully',
                'data'=>$data
            ],200);
        }
        catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

}

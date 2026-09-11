<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\EveryconditionTerm;
use Exception;
use Illuminate\Http\Request;

class FrontendProductCondoditionController extends Controller
{
    public function index()
    {
        try {
            $productConditions = EveryconditionTerm::where('status', 'active')->latest()->get()->map(function ($item) {
                return [
                    'id'          => $item->id,
                    'title'       => $item->title ?? '',
                    'subtitle'    => $item->subtitle ?? '',
                    'description' => $item->description ?? '',
                    'image'       => $item->image ? asset($item->image) : asset('no-image.png'),
                ];
            });

            return response()->json([
                'status'  => true,
                'message' => 'Product conditions fetched successfully',
                'data'    => $productConditions,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => 'Failed to fetch product conditions',
            ], 500);
        }
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Feature;
use App\Models\TaglineBar;
use App\Models\WhyEvSystem;
use Exception;
use Illuminate\Http\Request;

class FrontendLandingPageController extends Controller
{
    public function index(){
        try{
            $banners = Banner::query()->where('status', 'active')->latest()->get()->map(function($banner){
                return [
                    'id' => $banner->id??'',
                    'title' => $banner->title??'',
                    'subtitle' => $banner->subtitle??'',
                    'banner_logo' => $banner->banner_logo?asset($banner->banner_logo):asset('no-image.png'),
                    'banner_image' => $banner->banner_image?asset($banner->banner_image):asset('no-image.png'),
                ];
            });
            $taglines = TaglineBar::query()->where('status', 'active')->latest()->get()->map(function($tagline){
                return [
                    'id' => $tagline->id??'',
                    'title' => $tagline->title??'',
                    'sub_title' => $tagline->sub_title??'',
                ];
            });
            $features = Feature::query()->where('status', 'active')->latest()->get()->map(function($feature){
                return [
                    'id' => $feature->id??'',
                    'title' => $feature->title??'',
                    'description' => $feature->description??'',
                    'icon' => $feature->image?asset($feature->image):asset('no-image.png'),
                ];
            });
            $whyEvSystems = WhyEvSystem::query()->where('status', 'active')->latest()->get()->map(function($whyEvSystem){
                return [
                    'id' => $whyEvSystem->id??'',
                    'title' => $whyEvSystem->title??'',
                    'description' => $whyEvSystem->description??'',
                ];
            });

            return response()->json([
                'status' => 'success',
                'data' => [
                    'banners' => $banners,
                    'taglines' => $taglines,
                    'features' => $features,
                    'whyEvSystems' => $whyEvSystems,
                ],
            ]);
        }
        catch(Exception $e){
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}

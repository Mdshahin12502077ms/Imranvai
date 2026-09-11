<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AdminSetting;
use App\Models\SocialSetting;
use App\Models\SystemSetting;
use Exception;

class FrontendAdminSettingController extends Controller
{
    public function getAdminData()
    {
        try {
            $adminSetting = AdminSetting::first() ?? SystemSetting::first();
            $socialSetting = SocialSetting::first();

            $adminData = [
                'id'              => $adminSetting->id ?? null,
                'logo'            => isset($adminSetting->logo) && $adminSetting->logo ? asset($adminSetting->logo) : asset('no-image.png'),
                'mini_logo'       => isset($adminSetting->mini_logo) && $adminSetting->mini_logo ? asset($adminSetting->mini_logo) : asset('no-image.png'),
                'favicon'         => isset($adminSetting->favicon) && $adminSetting->favicon ? asset($adminSetting->favicon) : asset('no-image.png'),
                'system_title'    => $adminSetting->system_title ?? ($adminSetting->company_name ?? ''),
                'company_name'    => $adminSetting->company_name ?? '',
                'tag_line'        => $adminSetting->tag_line ?? '',
                'phone_number'    => $adminSetting->phone_number ?? '',
                'whatsapp_number' => $adminSetting->whatsapp_number ?? '',
                'email'           => $adminSetting->email ?? '',
                'company_address' => $adminSetting->company_address ?? '',
                'copyright_text'  => $adminSetting->copyright_text ?? '',
            ];

            $socialData = $socialSetting ? [
                'facebook_link'  => $socialSetting->facebook_link ?? '',
                'facebook_icon'  => $socialSetting->facebook_icon ? asset($socialSetting->facebook_icon) : null,
                'instagram_link' => $socialSetting->instagram_link ?? '',
                'instagram_icon' => $socialSetting->instagram_icon ? asset($socialSetting->instagram_icon) : null,
                'twitter_link'   => $socialSetting->twitter_link ?? '',
                'twitter_icon'   => $socialSetting->twitter_icon ? asset($socialSetting->twitter_icon) : null,
                'tiktok_link'    => $socialSetting->tiktok_link ?? '',
                'tiktok_icon'    => $socialSetting->tiktok_icon ? asset($socialSetting->tiktok_icon) : null,
                'whatsapp_link'  => $socialSetting->whatsapp_link ?? '',
                'whatsapp_icon'  => $socialSetting->whatsapp_icon ? asset($socialSetting->whatsapp_icon) : null,
                'linkedin_link'  => $socialSetting->linkedin_link ?? '',
                'linkedin_icon'  => $socialSetting->linkedin_icon ? asset($socialSetting->linkedin_icon) : null,
                'telegram_link'  => $socialSetting->telegram_link ?? '',
                'telegram_icon'  => $socialSetting->telegram_icon ? asset($socialSetting->telegram_icon) : null,
                'youtube_link'   => $socialSetting->youtube_link ?? '',
                'youtube_icon'   => $socialSetting->youtube_icon ? asset($socialSetting->youtube_icon) : null,
            ] : null;

            return response()->json([
                'status'  => true,
                'message' => 'Admin data fetched successfully',
                'data'    => [
                    'admin_setting'  => $adminData,
                    'social_setting' => $socialData,
                ],
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => 'Failed to fetch admin data',
            ], 500);
        }
    }
}

<?php

namespace App\Http\Controllers\Backend\Setting;

use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;

class PayPalSettingController extends Controller
{
    public function edit()
    {
        return view('backend.layouts.settings.paypal_settings');
    }

    public function update(Request $request)
    {
        $request->validate([
            'paypal_mode' => 'required|in:sandbox,live',
            'paypal_client_id' => 'required|string|max:255',
            'paypal_client_secret' => 'required|string|max:255',
            'paypal_success_url' => 'nullable|url|max:255',
            'paypal_cancel_url' => 'nullable|url|max:255',
        ]);

        try {
            $envPath = base_path('.env');
            $envContent = File::get($envPath);
            $lineBreak = "\n";

            // If keys do not exist in .env, append them first
            if (!str_contains($envContent, 'PAYPAL_MODE=')) {
                $envContent .= $lineBreak . 'PAYPAL_MODE=sandbox';
            }
            if (!str_contains($envContent, 'PAYPAL_SANDBOX_CLIENT_ID=')) {
                $envContent .= $lineBreak . 'PAYPAL_SANDBOX_CLIENT_ID=';
            }
            if (!str_contains($envContent, 'PAYPAL_SANDBOX_CLIENT_SECRET=')) {
                $envContent .= $lineBreak . 'PAYPAL_SANDBOX_CLIENT_SECRET=';
            }
            if (!str_contains($envContent, 'PAYPAL_SUCCESS_REDIRECT_URL=')) {
                $envContent .= $lineBreak . 'PAYPAL_SUCCESS_REDIRECT_URL=';
            }
            if (!str_contains($envContent, 'PAYPAL_CANCEL_REDIRECT_URL=')) {
                $envContent .= $lineBreak . 'PAYPAL_CANCEL_REDIRECT_URL=';
            }

            $envContent = preg_replace([
                '/PAYPAL_MODE=(.*)\s?/',
                '/PAYPAL_SANDBOX_CLIENT_ID=(.*)\s?/',
                '/PAYPAL_SANDBOX_CLIENT_SECRET=(.*)\s?/',
                '/PAYPAL_SUCCESS_REDIRECT_URL=(.*)\s?/',
                '/PAYPAL_CANCEL_REDIRECT_URL=(.*)\s?/',
            ], [
                'PAYPAL_MODE=' . $request->paypal_mode . $lineBreak,
                'PAYPAL_SANDBOX_CLIENT_ID=' . $request->paypal_client_id . $lineBreak,
                'PAYPAL_SANDBOX_CLIENT_SECRET=' . $request->paypal_client_secret . $lineBreak,
                'PAYPAL_SUCCESS_REDIRECT_URL=' . $request->paypal_success_url . $lineBreak,
                'PAYPAL_CANCEL_REDIRECT_URL=' . $request->paypal_cancel_url . $lineBreak,
            ], $envContent);

            File::put($envPath, $envContent);

            return back()->with('success', 'PayPal settings updated successfully!');
        } catch (Exception $e) {
            return back()->with('error', 'Failed to update PayPal settings: ' . $e->getMessage());
        }
    }
}

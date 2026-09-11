<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Subscriber;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SubscriberController extends Controller
{
    use ApiResponse;
    // Subscribe route: accepts all input fields
    public function subscribe(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'firstname' => 'nullable|string|max:255',
            'lastname'  => 'nullable|string|max:255',
            'email'     => 'required|email|unique:subscribers,email',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), 422);
        }

        $subscriber = Subscriber::create([
            'firstname' => $request->firstname,
            'lastname'  => $request->lastname,
            'email'     => $request->email,
            'status'    => 'subscribed',
        ]);

        return $this->successResponse($subscriber, 'Subscriber subscribed successfully!', 201);
    }

    // Unsubscribe route: only updates status
    public function unsubscribe(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), 422);
        }

        $subscriber = Subscriber::where('email', $request->email)->first();

        if (!$subscriber) {
            return $this->errorResponse('Email not found in subscribers list.', 404);
        }

        $subscriber->update(['status' => 'unsubscribed']);

        return $this->successResponse($subscriber, 'Subscriber unsubscribed successfully!', 200);
    }
}

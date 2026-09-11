<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class ContactMessageController extends Controller
{
    use ApiResponse;
    /**
     * Store a new contact message
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'surname' => 'nullable|string|max:255',
            'email' => 'required|email|max:255',
            'nation' => 'nullable|string|max:255',
            'activity' => 'nullable|string|max:255',
            'telephone' => 'nullable|string|max:255',
            'message' => 'nullable|string',
        ]);

        $contactMessage = ContactMessage::create([
            'name' => $validated['name'],
            'surname' => $validated['surname'] ?? null,
            'email' => $validated['email'],
            'nation' => $validated['nation'] ?? null,
            'activity' => $validated['activity'] ?? null,
            'telephone' => $validated['telephone'] ?? null,
            'message' => $validated['message'] ?? null,
            'status' => 'pending',
        ]);

        return $this->successResponse($contactMessage, 'Contact message submitted successfully.', 201);
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreInquiryRequest;
use App\Models\Branch;
use App\Models\Inquiry;
use Illuminate\Http\JsonResponse;

class InquiryController extends Controller
{
    public function __invoke(StoreInquiryRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $branch = Branch::query()->findOrFail($validated['branch_id']);
        $contact = preg_replace('/[^0-9+]/', '', $validated['contact']);
        $inquiry = Inquiry::query()
            ->where('branch_id', $branch->id)
            ->where('contact', $contact)
            ->where('created_at', '>=', now()->subMinutes(10))
            ->first();

        if (! $inquiry) {
            $inquiry = Inquiry::query()->create([
                'branch_id' => $branch->id,
                'branch_name' => $branch->name,
                'name' => $validated['name'],
                'contact' => $contact,
                'interest' => $validated['interest'],
                'status' => 'new',
                'ip_hash' => hash_hmac('sha256', $request->ip() ?: 'unknown', (string) config('app.key')),
            ]);
        }

        return response()->json([
            'message' => 'Thanks! Your inquiry was sent to '.$branch->name.'. Our team will contact you using the number provided.',
            'inquiry_id' => $inquiry->id,
        ], $inquiry->wasRecentlyCreated ? 201 : 200);
    }
}

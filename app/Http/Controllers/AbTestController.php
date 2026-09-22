<?php

namespace App\Http\Controllers;

use App\Services\ABTestingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AbTestController extends Controller
{
    public function track(Request $request, ABTestingService $abTest): JsonResponse
    {
        $validated = $request->validate([
            'event' => 'required|string',
            'variations' => 'array',
            'variant' => 'string',
        ]);

        $referer = $request->header('referer');
        if ($referer && str_contains($referer, '?')) {
            $referer = strtok($referer, '?');
        }

        $abTest->trackEvent($validated['event'], [
            'variant' => $validated['variant'] ?? null,
            'variations' => $validated['variations'] ?? [],
            'url' => $referer ? substr($referer, 0, 500) : null,
            'user_agent' => $request->userAgent() ? substr($request->userAgent(), 0, 500) : null,
        ]);

        return response()->json(['success' => true]);
    }
}

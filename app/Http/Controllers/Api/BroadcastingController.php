<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BroadcastingController
{
    public function auth(Request $request): JsonResponse
    {
        return response()->json(
            \Illuminate\Support\Facades\Broadcast::auth($request)
        );
    }
}

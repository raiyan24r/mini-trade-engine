<?php

namespace App\Http\Controllers\Api;

use App\Helpers\HttpResponse;
use App\Services\ProfileService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProfileController
{
    private ProfileService $profileService;

    public function __construct(ProfileService $profileService)
    {
        $this->profileService = $profileService;
    }

    /**
     * Get authenticated user's profile with balances
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function profile(Request $request): JsonResponse
    {
        $userId = $request->user()->id;

        $profile = $this->profileService->getUserProfile($userId);

        if (!$profile) {
            return HttpResponse::notFound('User profile not found');
        }

        return HttpResponse::success('Profile retrieved successfully', $profile);
    }
}

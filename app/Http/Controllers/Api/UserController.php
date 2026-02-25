<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Resources\UserProfileResource;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{

    /**
     * Create a user account. No authentication required per spec.
     */
    public function store(StoreUserRequest $request): JsonResponse
    {
        $user = User::query()->create($request->validated());

        return (new UserResource($user))->response()->setStatusCode(201);
    }

    /**
     * User profile: all wallets, each wallet balance, and total balance across wallets.
     */
    public function show(User $user): JsonResponse
    {
        return (new UserProfileResource($user))->response();
    }
}

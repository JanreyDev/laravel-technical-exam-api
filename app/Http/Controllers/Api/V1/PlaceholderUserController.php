<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\PlaceholderUser;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PlaceholderUserController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $perPage = min((int) $request->integer('per_page', 25), 100);

        $users = PlaceholderUser::query()
            ->with(['address', 'company'])
            ->orderBy('external_id')
            ->paginate($perPage);

        return response()->json($users);
    }

    public function show(int $externalId): JsonResponse
    {
        $user = PlaceholderUser::query()
            ->with(['address', 'company', 'posts', 'albums', 'todos'])
            ->where('external_id', $externalId)
            ->firstOrFail();

        return response()->json($user);
    }
}

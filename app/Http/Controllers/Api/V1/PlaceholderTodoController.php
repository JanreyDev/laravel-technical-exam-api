<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\PlaceholderTodo;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PlaceholderTodoController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $perPage = min((int) $request->integer('per_page', 25), 100);

        $todos = PlaceholderTodo::query()
            ->with(['user'])
            ->orderBy('external_id')
            ->paginate($perPage);

        return response()->json($todos);
    }

    public function show(int $externalId): JsonResponse
    {
        $todo = PlaceholderTodo::query()
            ->with(['user'])
            ->where('external_id', $externalId)
            ->firstOrFail();

        return response()->json($todo);
    }
}

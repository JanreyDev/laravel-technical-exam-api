<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\PlaceholderPost;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PlaceholderPostController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $perPage = min((int) $request->integer('per_page', 25), 100);

        $posts = PlaceholderPost::query()
            ->with(['user'])
            ->orderBy('external_id')
            ->paginate($perPage);

        return response()->json($posts);
    }

    public function show(int $externalId): JsonResponse
    {
        $post = PlaceholderPost::query()
            ->with(['user', 'comments'])
            ->where('external_id', $externalId)
            ->firstOrFail();

        return response()->json($post);
    }
}

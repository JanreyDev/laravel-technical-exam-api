<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\PlaceholderComment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PlaceholderCommentController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $perPage = min((int) $request->integer('per_page', 25), 100);

        $comments = PlaceholderComment::query()
            ->with(['post'])
            ->orderBy('external_id')
            ->paginate($perPage);

        return response()->json($comments);
    }

    public function show(int $externalId): JsonResponse
    {
        $comment = PlaceholderComment::query()
            ->with(['post'])
            ->where('external_id', $externalId)
            ->firstOrFail();

        return response()->json($comment);
    }
}

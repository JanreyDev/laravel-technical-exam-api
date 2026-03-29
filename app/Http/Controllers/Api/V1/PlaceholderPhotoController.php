<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\PlaceholderPhoto;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PlaceholderPhotoController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $perPage = min((int) $request->integer('per_page', 25), 100);

        $photos = PlaceholderPhoto::query()
            ->with(['album'])
            ->orderBy('external_id')
            ->paginate($perPage);

        return response()->json($photos);
    }

    public function show(int $externalId): JsonResponse
    {
        $photo = PlaceholderPhoto::query()
            ->with(['album'])
            ->where('external_id', $externalId)
            ->firstOrFail();

        return response()->json($photo);
    }
}

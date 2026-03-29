<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\PlaceholderAlbum;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PlaceholderAlbumController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $perPage = min((int) $request->integer('per_page', 25), 100);

        $albums = PlaceholderAlbum::query()
            ->with(['user'])
            ->orderBy('external_id')
            ->paginate($perPage);

        return response()->json($albums);
    }

    public function show(int $externalId): JsonResponse
    {
        $album = PlaceholderAlbum::query()
            ->with(['user', 'photos'])
            ->where('external_id', $externalId)
            ->firstOrFail();

        return response()->json($album);
    }
}

<?php

namespace App\Modules\Core\Http\Controllers\Admin;

use App\Exceptions\ApiException;
use App\Http\Controllers\Controller;
use App\Modules\Core\Http\Requests\Admin\UploadMediaRequest;
use App\Modules\Core\Http\Resources\Admin\MediaResource;
use App\Modules\Core\Models\Media;
use App\Modules\Core\Services\MediaService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MediaController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Media::query()->latest();

        if ($kind = $request->string('kind')->toString()) {
            match ($kind) {
                'image' => $query->where('mime', 'like', 'image/%'),
                'video' => $query->where('mime', 'like', 'video/%'),
                'other' => $query->where('mime', 'not like', 'image/%')->where('mime', 'not like', 'video/%'),
                default => null,
            };
        }

        if ($q = $request->string('q')->toString()) {
            $query->where('original_name', 'like', "%{$q}%");
        }

        $paginator = $query->paginate($request->integer('per_page', 40));

        return response()->json([
            'data' => MediaResource::collection($paginator->getCollection()),
            'meta' => [
                'pagination' => [
                    'page' => $paginator->currentPage(),
                    'per_page' => $paginator->perPage(),
                    'total' => $paginator->total(),
                    'last_page' => $paginator->lastPage(),
                ],
                'total_size' => (int) Media::query()->sum('size'),
            ],
        ]);
    }

    public function store(UploadMediaRequest $request, MediaService $media): JsonResponse
    {
        $uploaded = $media->upload($request->file('file'), $request->user()->id, $request->string('alt')->toString() ?: null);

        return response()->json(['data' => new MediaResource($uploaded)], 201);
    }

    public function destroy(Request $request, Media $media, MediaService $service): JsonResponse
    {
        $usages = DB::table('product_media')->where('media_id', $media->id)->count();

        if ($usages > 0 && ! $request->boolean('force')) {
            throw new ApiException(
                'MEDIA_IN_USE',
                "This file is attached to {$usages} product(s). Pass force=1 to delete anyway.",
                409,
                ['usages' => $usages],
            );
        }

        $service->delete($media);

        return response()->json(status: 204);
    }
}

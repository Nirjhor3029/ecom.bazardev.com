<?php

namespace App\Modules\Core\Services;

use App\Modules\Core\Models\Media;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

/**
 * Large raster images are downsized + re-compressed with Intervention
 * Image (GD driver) before they ever touch disk — SVG/GIF are left
 * untouched (vector / animation would break under resize+re-encode).
 */
class MediaService
{
    public const string DISK = 'public';

    protected const int MAX_DIMENSION = 1600;

    protected const int COMPRESS_ABOVE_BYTES = 400 * 1024;

    protected const int QUALITY = 82;

    protected const array RASTER_MIMES = ['image/jpeg', 'image/png', 'image/webp'];

    public function upload(UploadedFile $file, ?int $actorId = null, ?string $alt = null): Media
    {
        $folder = 'media/'.now()->format('Y/m');
        $extension = strtolower($file->getClientOriginalExtension() ?: $file->extension() ?: 'bin');
        $filename = Str::uuid()->toString().'.'.$extension;
        $relativePath = "{$folder}/{$filename}";
        $mime = $file->getMimeType() ?? 'application/octet-stream';

        Storage::disk(self::DISK)->makeDirectory($folder);

        $width = null;
        $height = null;

        if (in_array($mime, self::RASTER_MIMES, true)) {
            [$width, $height] = $this->storeCompressed($file, $relativePath);
        } else {
            Storage::disk(self::DISK)->putFileAs($folder, $file, $filename);
        }

        return Media::query()->create([
            'disk' => self::DISK,
            'path' => $relativePath,
            'original_name' => $file->getClientOriginalName(),
            'mime' => $mime,
            'size' => Storage::disk(self::DISK)->size($relativePath),
            'width' => $width,
            'height' => $height,
            'alt' => $alt,
            'created_by' => $actorId,
        ]);
    }

    public function delete(Media $media): void
    {
        Storage::disk($media->disk)->delete($media->path);
        $media->delete();
    }

    /** @return array{0: int, 1: int} [width, height] after any resize */
    protected function storeCompressed(UploadedFile $file, string $relativePath): array
    {
        $manager = new ImageManager(Driver::class);
        $image = $manager->decodeSplFileInfo($file);

        if ($image->width() > self::MAX_DIMENSION || $image->height() > self::MAX_DIMENSION) {
            $image->scaleDown(width: self::MAX_DIMENSION, height: self::MAX_DIMENSION);
        }

        $destination = Storage::disk(self::DISK)->path($relativePath);
        $needsQuality = $file->getSize() > self::COMPRESS_ABOVE_BYTES;

        $image->save($destination, quality: $needsQuality ? self::QUALITY : 95);

        return [$image->width(), $image->height()];
    }
}

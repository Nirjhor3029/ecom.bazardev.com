<?php

namespace App\Modules\Core\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Media extends Model
{
    protected $fillable = ['disk', 'path', 'original_name', 'mime', 'size', 'width', 'height', 'alt', 'created_by'];

    public function url(): string
    {
        return Storage::disk($this->disk)->url($this->path);
    }

    /** image | video | document | other — drives the gallery's icon/grid treatment. */
    public function kind(): string
    {
        return match (true) {
            str_starts_with($this->mime ?? '', 'image/') => 'image',
            str_starts_with($this->mime ?? '', 'video/') => 'video',
            in_array($this->mime, ['application/pdf', 'application/msword', 'application/vnd.ms-excel'], true),
            str_contains($this->mime ?? '', 'officedocument') => 'document',
            default => 'other',
        };
    }
}

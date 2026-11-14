<?php

namespace App\Modules\Core\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Media extends Model
{
    protected $fillable = ['disk', 'path', 'mime', 'size', 'width', 'height', 'alt', 'created_by'];

    public function url(): string
    {
        return Storage::disk($this->disk)->url($this->path);
    }
}

<?php

namespace App\Modules\Core\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UploadMediaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // 20MB cap — generous for product photography, still a sane ceiling.
            'file' => ['required', 'file', 'max:20480'],
            'alt' => ['nullable', 'string', 'max:255'],
        ];
    }
}

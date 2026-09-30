<?php

namespace App\Http\Requests\Folder;

use Illuminate\Foundation\Http\FormRequest;

class UpdateFolderRequest extends FormRequest
{
    public function authorize(): bool {
        return true;
    }

    public function rules(): array {
        return [
            'name' => ['sometimes', 'string', 'max:255',],

            'description' => ['sometimes', 'nullable', 'string',],
        ];
    }
}
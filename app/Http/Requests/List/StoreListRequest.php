<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreListRequest extends FormRequest
{
    public function authorize(): bool {
        return true;
    }

    public function rules(): array {
        return [
            'name' => ['required', 'string', 'max:255',],

            'description' => ['nullable', 'string',],

            'workspace_id' => ['required', 'integer', 'exists:workspaces,id',],

            'folder_id' => ['nullable', 'integer', 'exists:folders,id',],
        ];
    }
}
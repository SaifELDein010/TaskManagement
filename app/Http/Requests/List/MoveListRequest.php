<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MoveListRequest extends FormRequest
{
    public function authorize(): bool {
        return true;
    }

    public function rules(): array {
        return [
            'folder_id' => ['nullable', 'integer', 'exists:folders,id',],
        ];
    }
}
<?php

namespace App\Http\Requests\Comments;

use Illuminate\Foundation\Http\FormRequest;

class StoreCommentRequest extends FormRequest {
    public function authorize(): bool {
        return true;
    }

    public function rules(): array {
        return [
            'content' => ['required', 'string', 'max:5000',],

            'parent_comment_id' => ['nullable', 'integer', 'exists:comments,id',],
        ];
    }
}
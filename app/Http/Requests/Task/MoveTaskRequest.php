<?php

namespace App\Http\Requests\Task;

use Illuminate\Foundation\Http\FormRequest;

class MoveTaskRequest extends FormRequest {
    public function authorize(): bool {
        return true;
    }

    public function rules(): array {
        return [
            'list_id' => ['required', 'integer', 'exists:lists,id',],
        ];
    }
}
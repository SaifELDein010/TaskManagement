<?php

namespace App\Http\Requests\Task;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTaskStatusRequest extends FormRequest {
    public function authorize(): bool {
        return true;
    }

    public function rules(): array {
        return [
            'status' => ['required', 'string', Rule::in(['pending', 'in_progress', 'review', 'completed', 'cancelled',])],
        ];
    }
}
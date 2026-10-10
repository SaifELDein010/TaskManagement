<?php

namespace App\Http\Requests\Task;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTaskRequest extends FormRequest {
    public function authorize(): bool {
        return true;
    }

    public function rules(): array {
        return [
            'title' => ['sometimes', 'string', 'max:255'],
            'description' => ['sometimes', 'nullable', 'string'],
            'status' => ['sometimes', 'string', Rule::in(['pending', 'in_progress', 'review', 'completed', 'cancelled',]),],
            'priority' => ['sometimes', 'string', Rule::in(['low', 'medium', 'high', ]),],
            'due_date' => ['sometimes', 'nullable', 'date',],
            'assigned_to' => ['sometimes', 'nullable', 'integer', 'exists:users,id',],
        ];
    }
}
<?php

namespace App\Http\Requests\Task;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTaskRequest extends FormRequest {
    public function authorize(): bool {
        return true;
    }

    public function rules(): array {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'status' => ['nullable', 'string',Rule::in(['pending', 'in_progress', 'review', 'completed', 'cancelled',]), ],
            'priority' => ['nullable', 'string', Rule::in(['low', 'medium', 'high',]), ],
            'due_date' => ['nullable', 'date',],
            'assigned_to' => ['nullable', 'integer', 'exists:users,id',],
        ];
    }
}
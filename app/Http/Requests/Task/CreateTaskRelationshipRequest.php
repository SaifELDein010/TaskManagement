<?php

namespace App\Http\Requests\Task;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateTaskRelationshipRequest extends FormRequest {
    public function authorize(): bool{
        return true;
    }

    public function rules(): array {
        return [
            'related_task_id' => ['required', 'integer', 'exists:tasks,id',],

            'type' => ['required', 'string', Rule::in(['blocks', 'blocked_by', 'relates_to', 'duplicates',]),],
        ];
    }
}
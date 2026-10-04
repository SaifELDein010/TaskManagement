<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateListWorkflowRequest extends FormRequest {
    public function authorize(): bool {
        return true;
    }

    public function rules(): array {
        return [
            'statuses' => ['required', 'array', 'min:1',],

            'statuses.*.id' => ['required', 'string', 'distinct',],

            'statuses.*.name' => ['required', 'string', 'max:255',],

            'transitions' => ['required', 'array',],

            'transitions.*.from' => ['required', 'string',],

            'transitions.*.to' => ['required', 'string',],

            'defaults' => ['sometimes', 'array',],

            'defaults.assignee_id' => ['sometimes', 'nullable', 'integer', 'exists:users,id',],

            'defaults.priority' => ['sometimes', 'nullable', 'string', 'max:50',],

            'defaults.due_in_days' => ['sometimes', 'nullable', 'integer', 'min:0',],
        ];
    }
}
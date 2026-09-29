<?php

namespace App\Http\Requests\WorkspaceMember;

use Illuminate\Foundation\Http\FormRequest;

class UpdateWorkspaceMemberRequest extends FormRequest
{
    public function authorize(): bool {
        return true;
    }

    public function rules(): array {
        return [
            'is_owner' => [
                'sometimes',
                'boolean',
            ],
        ];
    }
}
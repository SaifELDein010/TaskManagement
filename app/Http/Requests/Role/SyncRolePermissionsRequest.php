<?php

namespace App\Http\Requests\Role;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SyncRolePermissionsRequest extends FormRequest
{
    public function authorize(): bool {
        return true;
    }

    public function rules(): array {
        return [
            'permissions' => ['required','array',],

            'permissions.*' => ['required','string', Rule::exists('permissions', 'name')->where('guard_name', 'api'),],
        ];
    }
}
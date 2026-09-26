<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool {
        return true;
    }

    public function rules(): array {
        return [
            'username' => ['required', 'string', 'unique:users,username',],

            'email' => ['required', 'email', 'unique:users,email',],

            'password' => ['required', 'string', 'min:8',],

            'role' => ['nullable', 'string',
                Rule::exists('roles', 'name')
                    ->where('guard_name', 'api'),
            ],
        ];
    }
}
<?php

namespace App\Http\Requests\WorkspaceMember;

use Illuminate\Foundation\Http\FormRequest;

class StoreWorkspaceMemberRequest extends FormRequest {
    public function authorize(): bool {
        return true;
    }

    public function rules(): array {
        return [
            'workspaceId' => ['required', 'integer', 'exists:workspaces,id',],

            'userId' => ['required', 'integer', 'exists:users,id',],
        ];
    }

    protected function prepareForValidation(): void {
        $this->merge([
            'workspaceId' => $this->route('id'),
            'userId' => $this->route('userId'),
        ]);
    }
}
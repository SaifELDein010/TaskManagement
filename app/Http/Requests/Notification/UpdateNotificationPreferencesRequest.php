<?php

namespace App\Http\Requests\Notification;

use App\Services\NotificationService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateNotificationPreferencesRequest extends FormRequest {
    public function authorize(): bool {
        return true;
    }

    public function rules(): array {
        return [
            'preferences' => ['required', 'array', 'min:1'],
            'preferences.*.event_type' => ['required', 'string', 'distinct', Rule::in(NotificationService::EVENT_TYPES)],
            'preferences.*.in_app' => ['required', 'boolean'],
            'preferences.*.email' => ['required', 'boolean'],
        ];
    }
}
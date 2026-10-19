<?php

namespace App\Http\Requests\Notification;

use App\Services\NotificationService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GetNotificationRequest extends FormRequest {
    public function authorize(): bool {
        return true;
    }

    public function rules(): array {
        return [
            'read' => ['nullable', 'boolean'],
            'type' => ['nullable', 'string', Rule::in(NotificationService::EVENT_TYPES)]
        ];
    }
}
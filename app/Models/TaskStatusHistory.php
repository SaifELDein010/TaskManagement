<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TaskStatusHistory extends Model {
    protected $fillable = [
        'task_id',
        'new_status',
        'changed_by',
    ];

    public function task(): BelongsTo {
        return $this->belongsTo(Task::class);
    }

    public function changedBy(): BelongsTo {
        return $this->belongsTo(User::class, 'changed_by');
    }
}
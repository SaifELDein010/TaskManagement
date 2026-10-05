<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TaskRelationship extends Model {
    protected $fillable = [
        'task_id',
        'related_task_id',
        'type',
    ];

    public function task(): BelongsTo {
        return $this->belongsTo(Task::class, 'task_id');
    }

    public function relatedTask(): BelongsTo {
        return $this->belongsTo(Task::class, 'related_task_id');
    }
}
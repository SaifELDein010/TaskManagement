<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Task extends Model {
    use SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'status',
        'priority',
        'due_date',
        'list_id',
        'created_by',
        'assigned_to',
    ];

    protected $casts = [
        'due_date' => 'datetime',
    ];

    public function list(): BelongsTo {
        return $this->belongsTo(TaskList::class, 'list_id');
    }

    public function creator(): BelongsTo {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function assignee(): BelongsTo {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function statusHistories(): HasMany {
        return $this->hasMany(TaskStatusHistory::class);
    }

    public function relationships(): HasMany{
        return $this->hasMany(TaskRelationship::class, 'task_id');
    }

    public function relatedRelationships(): HasMany {
        return $this->hasMany(TaskRelationship::class, 'related_task_id');
    }
}
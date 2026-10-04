<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
}
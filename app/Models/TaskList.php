<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TaskList extends Model
{
    use SoftDeletes;

    protected $table = 'lists';

    protected $fillable = [
        'name',
        'description',
        'workspace_id',
        'folder_id',
        'created_by',
        'workflow',
        'task_defaults',
    ];

    protected $casts = [
        'workflow' => 'array',
        'task_defaults' => 'array',
    ];
    
    public function workspace(): BelongsTo {
        return $this->belongsTo(Workspace::class);
    }

    public function folder(): BelongsTo{
        return $this->belongsTo(Folder::class);
    }

    public function creator(): BelongsTo{
        return $this->belongsTo(User::class, 'created_by');
    }

    public function tasks(): HasMany {
        return $this->hasMany(Task::class, 'list_id');
    }
}
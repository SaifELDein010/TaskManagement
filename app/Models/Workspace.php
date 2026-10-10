<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Folder;


class Workspace extends Model {

    use SoftDeletes;
    protected $fillable = [
        'name',
        'description'
    ];

    public function members(): HasMany{
        return $this->hasMany(WorkspaceMember::class);
    }

    public function folders(): HasMany {
        return $this->hasMany(Folder::class);
    }

    public function lists(): HasMany {
        return $this->hasMany(TaskList::class);
    }

    public function activityLogs(): HasMany {
        return $this->hasMany(ActivityLog::class);
    }
}

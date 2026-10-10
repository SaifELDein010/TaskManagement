<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Folder;

class User extends Authenticatable implements JWTSubject {

    use HasRoles;
    protected $fillable = [
        'username',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
    ];

    protected function casts(): array {
        return [
            'password' => 'hashed',
        ];
    }

    protected $guard_name = 'api';
    public function getJWTIdentifier() {
        return $this->getKey();
    }

    public function getJWTCustomClaims() {
        return [];
    }

    public function workspaceMemberships(): HasMany {
        return $this->hasMany(WorkspaceMember::class);
    }

    public function createdFolders(): HasMany {
        return $this->hasMany(Folder::class, 'created_by');
    }

    public function createdLists(): HasMany {
        return $this->hasMany(TaskList::class, 'created_by');
    }

    public function createdTasks(): HasMany {
        return $this->hasMany(Task::class, 'created_by');
    }

    public function assignedTasks(): HasMany {
        return $this->hasMany(Task::class, 'assigned_to');
    }

    public function comments(): HasMany {
        return $this->hasMany(Comment::class);
    }
}

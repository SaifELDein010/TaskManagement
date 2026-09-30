<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Folder extends Model {
    protected $fillable = [
        'name',
        'description',
        'workspace_id',
        'parent_id',
        'created_by',
    ];

    public function workspace(): BelongsTo {
        return $this->belongsTo(Workspace::class);
    }

    public function creator(): BelongsTo{
        return $this->belongsTo(User::class,'created_by');
    }

    public function parent(): BelongsTo{
        return $this->belongsTo(Folder::class,'parent_id');
    }

    public function children(): HasMany{
        return $this->hasMany(Folder::class,'parent_id');
    }
}
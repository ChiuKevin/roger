<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Models\Role as SpatieRole;


class Role extends SpatieRole
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'guard_name', 'status', 'is_disabled', 'remark'];

    protected $hidden = ['guard_name', 'deleted_at'];
}

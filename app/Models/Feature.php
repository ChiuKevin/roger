<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Feature extends Model
{
    protected $fillable = ['name'];

    public function children()
    {
        return $this->hasMany(Feature::class, 'parent_id');
    }

    public function parent()
    {
        return $this->belongsTo(Feature::class, 'parent_id');
    }
}

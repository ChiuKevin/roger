<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobCategoryMenu extends Model
{
    use HasFactory;

    protected $fillable = [
        'region',
        'parent_id',
        'image',
        'creator',
        'updater'
    ];

    public function children()
    {
        return $this->hasMany(JobCategoryMenu::class, 'parent_id');
    }

    public function parent()
    {
        return $this->belongsTo(JobCategoryMenu::class, 'parent_id');
    }

    public function categories()
    {
        return $this->belongsToMany(JobCategory::class, 'job_category_relations', 'menu_id', 'category_id')
            ->withPivot('is_primary');
    }
}

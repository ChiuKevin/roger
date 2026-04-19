<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'region',
        'type',
        'price',
        'image',
        'is_hot',
        'sort',
        'is_disabled',
        'creator',
        'updater'
    ];

    public function menus()
    {
        return $this->belongsToMany(JobCategoryMenu::class, 'job_category_relations', 'category_id', 'menu_id')
            ->withPivot('is_primary');
    }
}

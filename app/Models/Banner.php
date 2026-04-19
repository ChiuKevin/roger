<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    use HasFactory;

    protected $fillable = [
        'region',
        'link_type',
        'name',
        'position_type',
        'position_id',
        'menu_id',
        'sort',
        'image',
        'link',
        'is_disabled',
        'start_time',
        'end_time'
    ];

    public const POSITION_TYPE_MAPPING = [
        1 => 'homepage',
        2 => 'job_categories',
    ];

    public const HOMEPAGE_POSITION_MAPPING = [
        0 => 'homepage_top_banner',
        1 => 'homepage_middle_banner',
        2 => 'homepage_bottom_banner',
    ];

    public const JOB_CATEGORIES_POSITION_MAPPING = [
        0 => 'job_categories_top_banner',
        1 => 'job_categories_middle_banner',
    ];
}

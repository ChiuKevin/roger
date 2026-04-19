<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Quote extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'status',
        'job_category_id',
        'user_id',
        'credits',
        'qna'
    ];

    public function quotePros()
    {
        return $this->hasMany(QuotePro::class, 'quote_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuotePro extends Model
{
    protected $fillable = [
        'quote_id',
        'pro_id',
        'type',
        'price',
        'unit',
        'custom_unit',
        'is_hired'
    ];

    public function quote()
    {
        return $this->belongsTo(Quote::class, 'quote_id');
    }

    public function quoteProMessages()
    {
        return $this->hasMany(QuoteProMessage::class, 'quote_pro_id');
    }
}

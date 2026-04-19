<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuoteProMessage extends Model
{
    protected $fillable = [
        'quote_pro_id',
        'sender_id',
        'type',
        'message'
    ];

    public function quotePro()
    {
        return $this->belongsTo(QuotePro::class, 'quote_pro_id');
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }
}

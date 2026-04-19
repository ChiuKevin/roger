<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TagRelation extends Model
{
    use HasFactory;

    protected $fillable = [
        'entity_type',
        'entity_id',
        'tag_id',
    ];

    public function tag()
    {
        return $this->belongsTo(Tag::class);
    }

    public function entity()
    {
        switch ($this->entity_type) {
            case 1:
                return $this->belongsTo(User::class, 'entity_id');
            // case 2:
            //     return $this->belongsTo(Order::class, 'entity_id');
            // case 3:
            //     return $this->belongsTo(Merchant::class, 'entity_id');
            default:
                return null;
        }
    }
}

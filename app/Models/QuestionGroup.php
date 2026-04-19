<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuestionGroup extends Model
{
    protected $fillable = ['name', 'is_disabled'];

    public function questions()
    {
        return $this->belongsToMany(Question::class, 'question_group_questions')->with('options');
    }
}

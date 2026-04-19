<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable = ['category', 'type', 'is_addable', 'is_custom'];

    public function options()
    {
        return $this->hasMany(QuestionOption::class, 'question_id');
    }

    public function questionGroups()
    {
        return $this->belongsToMany(QuestionGroup::class, 'question_group_questions');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuestionGroupQuestion extends Model
{
    protected $fillable = ['question_group_id', 'question_id'];

    public $timestamps = false;
}

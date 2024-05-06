<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionAttempt extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'question_id',
        'is_correct',
        'is_review',
        'no_answer',
        'option'
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function question()
    {
        return $this->belongsTo(Question::class);
    }

}

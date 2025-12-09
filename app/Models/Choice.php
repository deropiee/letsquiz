<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Choice extends Model
{
    protected $fillable = ['question_id', 'identifier', 'choice_text', 'is_correct', 'mapped_value'];

    public function question()
    {
        return $this->belongsTo(Question::class);
    }
}

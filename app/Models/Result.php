<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Result extends Model
{
    protected $fillable = [
        'user_id',
        'category_id',
        'correct_answers',
        'wrong_answers',
        'time_taken',
        'gems_earned',
        'is_private'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function sharedUsers()
    {
        return $this->belongsToMany(User::class, 'result_user_shares');
    }
}

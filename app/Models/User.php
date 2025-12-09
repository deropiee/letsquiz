<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'gems',
        'avatar',
        'theme_color',
        'purchased_avatars',
        'purchased_theme_colors',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'purchased_avatars' => 'array',
            'purchased_theme_colors' => 'array',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (User $user) {
            // Defaults zonder automatische avatar1 unlock
            if (!isset($user->avatar)) {
                $user->avatar = null; // geen gekozen avatar
            }
            $user->theme_color = $user->theme_color ?? '#f3f4f6';
            $user->purchased_avatars = $user->purchased_avatars ?? []; // leeg; user moet zelf kopen
            $user->purchased_theme_colors = $user->purchased_theme_colors ?? ['#f3f4f6'];
        });
    }

    public function results()
    {
        return $this->hasMany(Result::class);
    }

    public function sharedResults()
    {
        return $this->belongsToMany(Result::class, 'result_user_shares');
    }
}

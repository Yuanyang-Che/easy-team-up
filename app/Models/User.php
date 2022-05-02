<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * @property string name
 * @property string email
 * @property string password
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function isAdmin(): bool
    {
        return $this->role->slug == 'admin';
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class)->where('favorite', '=', true);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function invitation()
    {
        return $this->hasMany(Invitation::class);
    }
}

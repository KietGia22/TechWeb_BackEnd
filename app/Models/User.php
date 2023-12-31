<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;
class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable, HasApiTokens;

    public $timestamps = false;

    public $incrementing = false;

    protected $table = 'user';
    protected $primaryKey = 'user_id';

    protected $fillable = ['user_id','name', 'email','phone', 'password', 'role','create_at'];
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $cast = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims() {
        return [];
    }

    public function cart():HasMany
    {
        return $this->hasMany(Cart::class, 'user_id', 'user_id');
    }

    public function order():HasMany
    {
        return $this->hasMany(Order::class, 'user_id', 'user_id');
    }

}

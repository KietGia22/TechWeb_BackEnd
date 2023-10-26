<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    public $timestamps = false;
    protected $table = 'user';
    protected $primaryKey = 'user_id';
    protected $fillable = ['user_id','name', 'email','phone', 'password', 'role_id','create_at'];
    protected $hidden = [
        'password'
    ];
}

<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Sanctum\HasApiTokens;

class Users extends Authenticatable
{
    use HasFactory, HasApiTokens;

    protected $fillable = [
        'username',
        'password'
    ];

    public function getTokenAttribute()
    {
        return $this->createToken('API Token')->plainTextToken;
    }

    // Metode untuk mendapatkan nama pengguna (user)
    public function getAuthIdentifierName()
    {
        return 'username';
    }

    // // Metode untuk mendapatkan kata sandi pengguna (user)
    public function getAuthPassword()
    {
        return $this->password;
    }
}

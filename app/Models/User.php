<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users';

    protected $fillable = [
        'fullName',
        'username',
        'email',
        'password',
        'dateOfBirth',
        'authorization',
        'role',
    ];

    protected $hidden = [
        'password',
    ];

    /*
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }
        */

    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    public function authorization(){
        return $this->authorization;
    }
    public function role(){
        return $this->role;
    }
}

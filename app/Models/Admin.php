<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable
{
    use Notifiable;

    protected $table = 'admins';

    public $timestamps = false;

    protected $fillable = [
        'name',
        'full_name',
        'username',
        'email',
        'password',
        'created_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];
}

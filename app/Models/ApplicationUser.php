<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApplicationUser extends Model
{
    protected $table = 'application_users';

    public $timestamps = false;

    protected $fillable = [
        'user_name',
        'visit_date',
        'visit_time',
        'device_type',
        'ip_address',
        'user_agent',
    ];
}


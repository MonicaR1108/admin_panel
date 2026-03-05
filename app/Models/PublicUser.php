<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PublicUser extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'ID';

    public $timestamps = false;

    protected $fillable = [
        'name',
        'email',
        'email_verified_at',
        'mobile',
        'address',
        'BusinessName',
        'username',
        'password',
        'status',
        'pending_status',
        'otp',
        'otp_expiry',
        'verified',
        'access_token',
        'access_token_expiry',
        'refresh_token',
        'refresh_token_expiry',
        'created_on',
        'created_by',
        'updated_on',
        'updated_by',
    ];

    protected $hidden = [
        'password',
        'access_token',
        'refresh_token',
    ];
}

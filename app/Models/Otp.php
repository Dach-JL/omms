<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Otp extends Model
{
    /**
     * The attributes that are mass assignable.
     * These match the columns we created in the database.
     */
    protected $fillable = [
        'email',
        'otp_code',
        'expires_at',
        'is_used',
    ];

    /**
     * The attributes that should be cast to native types.
     */
    protected $casts = [
        'expires_at' => 'datetime',
        'is_used' => 'boolean',
    ];
}
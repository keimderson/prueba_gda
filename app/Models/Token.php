<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Token extends Model
{
    protected $fillable = [
        'id',
        'user_id',
        'token',
        'user',
        'expires_at'
    ];
}

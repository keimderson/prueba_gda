<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Communes extends Model
{
    public $timestamps = false;

    protected $primaryKey = 'id_com';

    protected $table = 'communes';

    protected $fillable = [
        'id_reg',
        'id_com',
        'description',
        'status'
    ];
}

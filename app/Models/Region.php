<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    public $timestamps = false;

    protected $primaryKey = 'id_reg';
    
    protected $table = 'regions';

    protected $fillable = [
        'id_reg',
        'description',
        'status'
    ];
}

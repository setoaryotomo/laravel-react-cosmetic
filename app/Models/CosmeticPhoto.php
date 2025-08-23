<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CosmeticPhoto extends Model
{
    //
    use SoftDeletes;

    protected $fillable = [
        'name',
        'cosmetic_id',
    ];

}

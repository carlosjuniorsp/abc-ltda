<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sales extends Model
{
    use SoftDeletes;
    protected $table = 'tb_products';

    protected $fillable = [
        'id',
        'amount',
        'products',
    ];

    protected $hidden = [
        'updated_at'
    ];
}
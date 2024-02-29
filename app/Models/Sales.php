<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sales extends Model
{
    use SoftDeletes;
    protected $table = 'tb_sales';

    protected $fillable = [
        'id',
        'product_id',
        'price',
        'quantity',
        'created_at'
    ];

    protected $hidden = [
        'updated_at',
        'deleted_at'
    ];
}

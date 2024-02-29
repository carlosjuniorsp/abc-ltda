<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Products extends Model
{
    use SoftDeletes;
    protected $table = 'tb_products';

    protected $fillable = [
        'name',
        'price',
        'description',
    ];

    protected $hidden = [
        'updated_at'
    ];

    public function sales()
    {
        return $this->hasOne(Sales::class, 'product_id');
    }
}

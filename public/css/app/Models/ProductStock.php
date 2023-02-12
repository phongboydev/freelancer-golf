<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductStock extends Model
{
    protected $table = 'product_stocks';
    protected $fillable =[
        'id',
        'product_id',
        'title',
        'slug',
        'price_origin',
        'price_promotion',
        'stock',
        'thumbnail',
        'sku',
        'key_option',
        'variable_data',
        'is_main',
        'sort',
        'created_at',
        'updated_at'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id','id');
    }
}

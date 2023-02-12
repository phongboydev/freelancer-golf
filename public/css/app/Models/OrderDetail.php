<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    protected $table = 'order_details';
    protected $fillable =[
        'id',
        'order_id',
        'product_id',
        'product_parent_id',
        'quantity',
        'price',
        'price_promotion',
        'price_origin',
        'total',
        'product_name',
        'product_sku',
        'product_thumbnail',
        'product_variants',
        'created_at',
        'updated_at'
    ];

    public function order() {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rating_Product extends Model
{
    protected $table = 'rating_products';
    protected $fillable = [
        'id',
        'product_id',
        'user_id',
        'name',
        'product_name',
        'product_variable_id',
        'rating',
        'status',
        'already_bought',
        'link_product',
        'review',
        'created_at',
        'updated_at'
    ];

    public function product() {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function user(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}

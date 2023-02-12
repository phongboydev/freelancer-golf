<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wishlist extends Model
{
    public $timestamps = false;
    protected $table = 'wishlist';
    protected $fillable =[
        'id',
        'product_id',
        'user_id',
        'created_at',
        'updated_at'
    ];

    public function product() {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
}

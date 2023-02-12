<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $table = 'carts';
    protected $fillable =[
        'id',
        'user_id',
        'product_id',
        'quantity',
        'created_at',
        'updated_at'
    ];

    public function user() {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}

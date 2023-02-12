<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Join_Category_Product extends Model
{
    public $timestamps = false;
    protected $table = 'join_category_product';
    protected $fillable =[
        'id',
        'product_id',
        'category_id'
    ];
}

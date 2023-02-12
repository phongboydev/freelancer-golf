<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Variable_Product extends Model
{
    protected $table = 'variable_products';
    protected $fillable =[
        'id',
        'name',
        'parent',
        'name_en',
        'status',
        'slug',
        'color_code',
        'created_at',
        'updated_at'
    ];
}

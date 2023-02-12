<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model
{
    protected $table = 'product_categories';
    protected $fillable =[
        'id',
        'name',
        'slug',
        'name_en',
        'content',
        'content_en',
        'description',
        'description_en',
        'parent',
        'sort',
        'seo_title',
        'seo_keyword',
        'seo_description',
        'thumbnail',
        'thumbnail_alt',
        'created_at',
        'updated_at',
        'status',
        'show_in_home',
        'galleries'
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'join_category_product','category_id', 'product_id');
    }
}

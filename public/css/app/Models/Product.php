<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'products';
    protected $fillable =[
        'id',
        'title',
        'subtitle',
        'sku',
        'slug',
        'description',
        'content',
        'price_origin',
        'price_promotion',
        'start_event',
        'end_event',
        'title_en',
        'description_en',
        'content_en',
        'thumbnail',
        'thumbnail_alt',
		'store_status',
        'created_at',
        'updated_at',
        'status',
        'gallery_images',
        'seo_title',
        'seo_keyword',
        'seo_description',
        'sort',
        'category_support_search',
        'group_variable_product',
        'new_arrival',
        'hot_deal',
        'best_seller',
        'propose',
        'category_primary_id',
        'categories',
        'rating',
        'total_rating'
    ];

    public function product_categories()
    {
        return $this->belongsToMany(ProductCategory::class, 'join_category_product','product_id', 'category_id');
    }

    public function product_stocks()
    {
        return $this->hasMany(ProductStock::class, 'product_id','id');
    }
}

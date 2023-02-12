<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    public $timestamps = false;
    protected $table = 'post';
    protected $fillable =[
        'id',
        'title',
        'slug',
        'description',
        'content',
        'title_en',
        'description_en',
        'content_en',
        'thumbnail',
        'thumbnail_alt',
        'created',
        'updated',
        'status',
        'gallery_images',
        'seo_title',
        'seo_keyword',
        'seo_description',
        'gallery_checked',
        'order_short',
        'hit',
        'enable_edit',
        'admin_status'
    ];
}

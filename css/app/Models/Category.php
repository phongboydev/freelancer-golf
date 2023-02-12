<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    public $timestamps = false;
    protected $table = 'categories';
    protected $fillable =[
        'categoryID',
        'categoryName',
        'categorySlug',
        'categoryName_en',
        'categoryDescription_en',
        'categoryDescription',
        'categoryParent',
        'categoryShort',
        'categoryIndex',
        'seo_title',
        'seo_keyword',
        'seo_description',
        'thumbnail',
        'thumbnail_alt',
        'created',
        'updated',
        'status'
    ];
}

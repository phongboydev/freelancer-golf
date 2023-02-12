<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    public $timestamps = false;
    protected $table = 'brand';
    protected $fillable =[
        'brandID',
        'brandName',
        'brandSlug',
        'brandDescription',
        'brandContent',
        'brandThumb',
        'brandThumb_alt',
        'brandStatus',
        'created',
        'updated',
        'seo_title',
        'seo_keyword',
        'seo_description'
    ];
}

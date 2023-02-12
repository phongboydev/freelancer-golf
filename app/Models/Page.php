<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    public $timestamps = false;
    protected $table = 'page';
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
        'template',
        'created',
        'updated',
        'status'
    ];
}

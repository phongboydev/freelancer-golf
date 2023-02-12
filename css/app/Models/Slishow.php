<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Slishow extends Model
{
    public $timestamps = false;
    protected $table = 'slishow';
    protected $fillable =[
        'id',
        'name',
        'src',
        'src_mobile',
        'order',
        'link',
        'description',
        'target',
        'created',
        'updated',
        'status',
        'video_link_slider',
        'video_link_slider_mobile'
    ];
}

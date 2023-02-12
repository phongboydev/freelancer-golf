<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Join_Category_Post extends Model
{
    public $timestamps = false;
    protected $table = 'join_category_post';
    protected $fillable =[
        'join_category_postID',
        'id_post',
        'id_category'
    ];
}

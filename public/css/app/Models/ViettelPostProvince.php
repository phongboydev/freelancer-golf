<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ViettelPostProvince extends Model
{
    protected $table = 'viettel_post_provinces';
    protected $fillable =[
        'id',
        'code',
        'name',
        'created_at',
        'updated_at'
    ];
}

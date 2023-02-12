<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ViettelPostWard extends Model
{
    protected $table = 'viettel_post_wards';
    protected $fillable =[
        'id',
        'district_id',
        'name',
        'created_at',
        'updated_at'
    ];
}

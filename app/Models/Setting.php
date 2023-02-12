<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    public $timestamps = false;
    protected $table = 'setting';
    protected $fillable =[
        'id',
        'name_setting',
        'value_setting',
        'created',
        'updated',
        'status'
    ];
}

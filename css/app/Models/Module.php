<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    protected $table = 'modules';
    protected $fillable = [
        'id',
        'title',
        'slug',
        'created_at',
        'updated_at'
    ];

    public function role()
    {
        return $this->hasMany(RoleDetail::class);
    }
}

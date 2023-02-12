<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $table = 'roles';
    protected $fillable = [
        'id',
        'name',
        'slug',
        'created_at',
        'updated_at'
    ];

    public function details()
    {
        return $this->hasMany(RoleDetail::class);
    }

    public function admins()
    {
        return $this->hasMany(Admin::class);
    }
}

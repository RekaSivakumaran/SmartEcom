<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserModel extends Model
{
    protected $table = 'users';
    protected $fillable = [
    'name',
    'email',
    'password',
    'role_id'
];

public function role()
    {
        return $this->belongsTo(RoleModel::class, 'role_id');
    }

}

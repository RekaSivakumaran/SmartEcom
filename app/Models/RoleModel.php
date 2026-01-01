<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class RoleModel extends Model
{
    use HasFactory;

    protected $table = 'roles';  // Map to 'roles' table

    protected $fillable = ['name', 'is_active'];

    protected $attributes = [
        'is_active' => true,
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerModel extends Model
{
     protected $table = 'customers';

     protected $fillable = ['name', 'email', 'mobile', 'password', 'status'];

      public $timestamps = false;

      public function orders()
      {
            return $this->hasMany(\App\Models\orderModel::class, 'customer_id');
      }
}

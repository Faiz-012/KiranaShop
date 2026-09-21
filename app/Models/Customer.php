<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
      protected $fillable = [
        'name','phone','address'
    ];

    public function customerLedger(){
      return $this->hasMany(CustomerLedger::class);
    }

    public function sales(){
      return $this->hasMany(Sale::class);
    }

}

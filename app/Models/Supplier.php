<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $fillable = [
        'name','company_name','phone'
    ];

    public function purchases(){
        return $this->hasMany(Purchase::class);
    }
}

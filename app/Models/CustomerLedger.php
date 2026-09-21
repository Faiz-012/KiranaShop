<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerLedger extends Model
{
    protected $fillable = [
        'customer_id','type','amount','note','date'
    ];

    public function customer(){
        return $this->belongsTo(Customer::class);
    }
}

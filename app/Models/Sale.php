<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $fillable = [
        'customer_name','total_amount','payment_type','sale_date',
    ];

    public function customer(){
        return $this->belongsTo(Customer::class);
    }

    public function saleitems(){
        return $this->hasMany(SaleItem::class);
    }
}

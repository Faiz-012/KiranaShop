<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    protected $fillable = [
        'supplier_id','total_amount','paid_amount','due_amount','purchase_date'
    ];

    public function supplier(){
        return $this->belongsTo(Supplier::class);
    }

    public function purchaseItems(){
        return $this->hasMany(PurchaseItem::class);
    }
}

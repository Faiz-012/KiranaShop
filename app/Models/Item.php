<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = [
        'category_id',
        'name',
        'purchase_price',
        'selling_price',
        'quantity',
        'unit',
        'low_stock_alert'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function purchaseitems()
    {
        return $this->hasMany(PurchaseItem::class);
    }

    public function saleItems()
{
    return $this->hasMany(SaleItem::class);
}
    
}

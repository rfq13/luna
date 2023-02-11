<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $appends = ['products_count'];
    function getProductsCountAttribute()
    {
        return Supply::where('supplier_id', $this->id)
        ->join('supply_products', 'supplies.id', '=', 'supply_products.supply_id')
        ->distinct('supply_products.product_id')
        ->count();
    }
}

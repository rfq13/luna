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
        return Supply::select("product_id")
            ->distinct()
            ->where("supplier_id", $this->id)
            ->get()
            ->count();
    }
}

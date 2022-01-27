<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplyHistory extends Model
{
    use HasFactory;

    function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
    function product()
    {
        return $this->belongsTo(Product::class);
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplyHistory extends Model
{
    use HasFactory;

    function supply()
    {
        return $this->belongsTo(Supply::class);
    }
    function product()
    {
        return $this->belongsTo(Product::class);
    }
}

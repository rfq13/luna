<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supply extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_supply', 'total_harga', 'supplier_id',
    ];

    function supply_product()
    {
        return $this->hasMany(SupplyProduct::class);
    }

    function supply_history()
    {
        return $this->hasMany(SupplyHistory::class);
    }

    function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
}

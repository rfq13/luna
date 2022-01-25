<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Supply extends Model
{
    // Initialize
    protected $fillable = [
        'kode_barang', 'nama_barang', 'jumlah', 'harga_beli', 'id_pemasok', 'pemasok',
    ];

    function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
    function product()
    {
        return $this->belongsTo(Product::class);
    }

    protected static function boot()
    {
        parent::boot();
        static::created(function ($supply) {
            if ($supply->jumlah > 0) {
                $sh = new SupplyHistory;
                $sh->product_id = $supply->product_id;
                $sh->jumlah = $supply->jumlah;
                $sh->harga_beli = $supply->harga_beli;
                $sh->ppn = $supply->ppn;
                $sh->supplier_id = $supply->supplier_id;
                $sh->save();
            }
        });
    }
}

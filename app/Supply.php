<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use App\Helpers\Stock;

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

            if ($supply->jumlah > 0 && $supply->show_stock != 1) {
                $sh = Stock::manipulateModel($supply, (new SupplyHistory), ['product_id', 'jumlah', 'harga_beli', 'supplier_id', 'ppn']);
                $sh->save();
            }
        });
    }
}

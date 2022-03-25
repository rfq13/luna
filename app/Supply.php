<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use App\Helpers\Stock;

class Supply extends Model
{
    // Initialize
    protected $fillable = [
        'kode_barang', 'nama_barang', 'jumlah', 'harga_beli', 'supplier_id', 'pemasok',
    ];

    function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
    function product()
    {
        return $this->belongsTo(Product::class);
    }
    function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    protected static function boot()
    {
        parent::boot();
        static::created(function ($supply) {

            if ($supply->jumlah > 0 && $supply->show_stock != 1) {
                $sh = Stock::manipulateModel($supply, (new SupplyHistory), [],['transaction_id','show_stock','from_supply_id','created_at','updated_at','distribution_id']);
                $sh->save();
            }
        });
    }
}

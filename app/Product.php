<?php

namespace App;

use App\Helpers\Stock;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $appends = ['stok'];
    // Initialize
    protected $fillable = [
        'kode_barang', 'jenis_barang', 'nama_barang', 'berat_barang', 'merek', 'stok', 'harga', 'keterangan',
    ];

    function supply()
    {
        return $this->hasMany(Supply::class);
    }

    function satuan()
    {
        return $this->belongsTo(Unit::class, "unit_id");
    }

    function getStokAttribute()
    {
        return Stock::qty($this->id);
    }
}

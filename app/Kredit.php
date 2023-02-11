<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
class Kredit extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    function repayments()
    {
        return $this->hasMany(Repayment::class);
    }

    function transaction(){
        return $this->belongsTo(Transaction::class,'transaction_code', 'kode_transaksi');
    }

    function customer(){
        return $this->belongsTo(Customer::class);
    }
}

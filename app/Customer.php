<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
class Customer extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];
    protected $appends = ['sisa_plafon'];

    // custom attribute sisa_plafon
    public function getSisaPlafonAttribute()
    {
        $kredit = $this->kredits()->sum('remaining_installment');

        return $this->plafon - $kredit;
    }

    function kredits()
    {
        return $this->hasMany(Kredit::class);
    }

    function repayments()
    {
        return $this->hasMany(Repayment::class);
    }

}

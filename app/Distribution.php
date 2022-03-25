<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Distribution extends Model
{
    use HasFactory;
    protected $guarded = [];

    function detail()
    {
        return $this->hasMany(Supply::class,'distribution_id');
    }
}

<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Auth;

class User
{
    public static function isCabang()
    {
        return Auth::user()->branch_id == 0 ? false : true;
    }
}

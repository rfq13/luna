<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class User
{
    public static function isCabang()
    {
        return Auth::user()->branch_id == 0 ? false : true;
    }

    public static function isMain()
    {
        return DB::table("general_settings")->select("main_user_id")->first()->main_user_id == Auth::id();
    }
}

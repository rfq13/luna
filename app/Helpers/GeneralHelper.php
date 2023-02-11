<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class GeneralHelper
{
    public static function generateCode(){
        // generate code based on date
        return date("YmdHis") . "-" . rand(1000, 9999);
    }
}

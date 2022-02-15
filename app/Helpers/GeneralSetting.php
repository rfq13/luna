<?php

namespace App\Helpers;

use App\GeneralSetting as GsModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GeneralSetting
{
    public static function set($key, $value)
    {
        $data = GsModel::first();
        if (!$data) {
            $data = new GsModel;
        }
        $data->$key = $value;
        return $data->save();
    }

    public static function get($key = "*")
    {
        $data = GsModel::select($key)->first();
        if ($data && $key != "*" && strpos($key, ",") == false) {
            $data = $data->$key;
        }
        return $data;
    }
}

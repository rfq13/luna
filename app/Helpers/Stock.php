<?php

namespace App\Helpers;

use App\Supply;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Stock
{
    public static function reduceStock($product_id, $stokReduce)
    {
        $cond = ['product_id' => $product_id, "processed" => 0];
        $current_stock = self::qty($product_id);
        $processed = [];
        if ($current_stock > $stokReduce) {
            DB::beginTransaction();
            try {
                $remainingReducingStok = $stokReduce;

                do {
                    $supply = Supply::where($cond)
                        ->where("jumlah", ">", 0)
                        ->whereNotIn("id", $processed)
                        ->first();

                    $reduce = $supply->jumlah - abs($remainingReducingStok);

                    array_push($processed, $supply->id);

                    $supply->processed = 1;
                    $supply->save();

                    $newSupply = self::manipulateModel($supply, (new Supply), ['product_id', 'harga_beli', 'supplier_id', 'ppn']);
                    $newSupply->jumlah = -$supply->jumlah;
                    $newSupply->save();

                    if ($reduce > 0) {

                        $newSupply = self::manipulateModel($supply, (new Supply), ['product_id', 'harga_beli', 'supplier_id', 'ppn']);
                        $newSupply->jumlah = $reduce;
                        $newSupply->show_stock = 0;
                        $newSupply->save();
                    }

                    $remainingReducingStok = $reduce < 0 ? $reduce : 0;

                    DB::commit();
                } while ($remainingReducingStok < 0);
            } catch (\Throwable $th) {
                Log::error(json_encode([
                    "message" => $th->getMessage(),
                    "line" => $th->getLine(),
                ]));
                return false;
            }


            return true;
        }

        return false;
    }

    public static function manipulateModel($from, $to, array $key)
    {
        foreach ($key as $q) {
            $to->$q = $from->$q;
        }

        return $to;
    }

    public static function qty($id)
    {
        return DB::table("supplies")->select('jumlah')->where([
            "product_id" => $id,
            // "show_stock" => 1
        ])->sum("jumlah");
    }
}

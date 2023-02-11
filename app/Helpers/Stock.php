<?php

namespace App\Helpers;

use App\SupplyProduct;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class Stock
{
    protected static $processed = [];
    protected static $manipulateKeys = [];
    protected static $errors = [];

    public static function reduceStock($product_id, $stokReduce, $transaction_id=null, $branch_id=0)
    {
        $cond = ['product_id' => $product_id, "processed" => 0,"branch_id"=>$branch_id];
        $current_stock = self::qty($product_id,$branch_id);
        // dd($current_stock,$product_id,$stokReduce,$branch_id);
        $processed = [];
        if ($current_stock >= $stokReduce) {
            DB::beginTransaction();
            try {
                $remainingReducingStok = $stokReduce;

                do {
                    $supply = SupplyProduct::where($cond)
                        ->where("jumlah", ">", 0)
                        ->whereNotIn("id", $processed)
                        ->first();

                    $reduce = $supply->jumlah - abs($remainingReducingStok);

                    array_push($processed, ["supply"=>$supply,"jumlah"=>$supply->jumlah-$reduce]);

                    $supply->processed = 1;
                    $supply->save();

                    $newSupply = self::manipulateModel($supply, (new SupplyProduct), ['product_id', 'harga_beli', 'supply_id', 'total_harga_beli', 'ppn']);
                    $newSupply->jumlah = -$supply->jumlah;
                    $newSupply->branch_id = $supply->branch_id;
                    $newSupply->show_stock = 0;
                    $newSupply->transaction_id = $transaction_id;
                    $newSupply->save();

                    if ($reduce > 0) {

                        $newSupply = self::manipulateModel($supply, (new SupplyProduct), ['product_id', 'harga_beli', 'supply_id', 'total_harga_beli', 'ppn','transaction_id']);
                        $newSupply->jumlah = $reduce;
                        $newSupply->show_stock = 1;
                        $newSupply->transaction_id = $transaction_id;
                        $newSupply->save();
                    }

                    $remainingReducingStok = $reduce < 0 ? $reduce : 0;

                    DB::commit();
                } while ($remainingReducingStok < 0);

                self::$processed = $processed;

            } catch (\Throwable $th) {
                self::$errors = [
                    "message" => $th->getMessage(),
                    "line" => $th->getLine(),
                ];

                Log::error(json_encode(self::$errors));
                return $th->getMessage()." ... \n ".$th->getLine();
            }

            return true;
        }

        return false;
    }

    public static function manipulateModel($from, $to, array $key,$unset=[])
    {
        if (count($key) <= 0) {
            $key = array_keys($from->toArray());

            for ($i=0; $i < count($unset); $i++) {
                if (($unkey = array_search($unset[$i], $key)) !== false) {
                    unset($key[$unkey]);
                }
            }

            if (($unkey = array_search("id", $key)) !== false) {
                unset($key[$unkey]);
            }

            $key = array_values($key);

            self::$manipulateKeys = $key;
        }

        foreach ($key as $q) {
            $to->$q = $from->$q;
        }

        return $to;
    }

    public static function qty($id,$branch_id=0)
    {
        return DB::table("supply_products")->select('jumlah')->where([
            "product_id" => $id,
            "branch_id" => $branch_id,
        ])->sum("jumlah");
    }

    public static function transferToBranch($product_id,$qty,$to,$from=0)
    {
        self::reduceStock($product_id,$qty,null,$from);

        foreach (self::$processed as $key => $supply) {
            $newSupply = self::manipulateModel($supply['supply'], (new SupplyProduct), [],['id','processed']);

            $newSupply->jumlah = $supply['jumlah'];
            $newSupply->branch_id = $to;
            $newSupply->show_stock = 0;
            $newSupply->from_supply_id = $supply['supply']->id;
            $newSupply->save();

        }

        // dd(array_keys(self::$processed[0]['supply']->toArray()));
    }


    function errors()
    {
        return self::$errors;
    }
}

<?

use Illuminate\Support\Facades\DB;

if (!function_exists("reduceStock")) {
    function reduceStock($product_id, $stok)
    {
        $current_stock = DB::table("supplies")->where(['id' => $product_id])->sum("stok");
        if ($current_stock) {
            # code...
        }
    }
}

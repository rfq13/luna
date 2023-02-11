<?php

namespace App\Imports;

use App\Helpers\GeneralHelper;
use App\SupplyProduct;
use App\Supply;
use App\Product;
use App\Supplier;
use App\Unit;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
class SupplyImport implements ToModel, WithValidation, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    private $supply;
     public function __construct($supply){
        $this->supply = $supply;
     }

    use Importable;

    public function model(array $row)
    {
        $header = [
            "kode_barang",
            "nama_barang",
            "satuan_berat",
            "berat_per_barang",
            "jumlah_barang",
            "total_harga_beli",
            "harga_jual_ecer",
            "harga_jual_grosir",
            "harga_jual_extra",
            "harga_jual_khusus",
            "ppn"
        ];
        DB::beginTransaction();
        // first or create unit
        $unit = Unit::where(['name' => $row[$header[2]]])->first();
        if(!$unit){
            $unit = new Unit();
            $unit->name = $row[$header[2]];
            $unit->save();
        }

        if($row[$header[0]]){
            $product = Product::where(['kode_barang' => $row[$header[0]]])->first();
            if(!$product){
                $product = new Product();
                $product->kode_barang = $row[$header[0]];
                $product->unit_id = $unit->id;
            }else{
                $product->unit_id = $unit->id;
            }
        }else{

            $product = new Product();
            $product->kode_barang = str_replace("-","",GeneralHelper::generateCode());
            $product->unit_id = $unit->id;
        }

        $product->harga_ecer = $row[$header[6]];
        $product->harga_grosir = $row[$header[7]];
        $product->harga_extra = $row[$header[8]];
        $product->harga_khusus = $row[$header[9]];
        $product->jenis_barang = 'Konsumtif';
        $product->nama_barang = $row[$header[1]];
        $product->berat_barang = $row[$header[3]];

        if ($row[$header[4]] > 0) {
            $product->keterangan = 'Tersedia';
        }else{
            $product->keterangan = 'Kosong';
        }
        $product->save();

        $supply_product = new SupplyProduct;

        $supply_product->jumlah = $row[$header[4]];
        $supply_product->harga_beli = $row[$header[5]] / $row[$header[4]];
        $supply_product->total_harga_beli = $row[$header[5]];
        $supply_product->supply_id = $this->supply->id;
        // $supply_product->supplier_id = $req->supplier_id[$no];
        $supply_product->product_id = $product->id;
        $supply_product->ppn = $row[$header[10]];
        $supply_product->save();

        $this->supply->total_harga += $row[$header[5]];
        $this->supply->save();

        DB::commit();
        return $supply_product;
    }

    public function rules(): array
    {
        return [
            // '0' => function ($attribute, $value, $onFailure) {
            //     if (Product::where('kode_barang', '=', $value)->count() == 0) {
            //         $onFailure('tidak tersedia');
            //     } elseif ($value == null || $value == '') {
            //         $onFailure('kosong');
            //     } elseif ($value == 0) {
            //         $onFailure('nol');
            //     }
            // },
            // '0' => 'nullable',
            // '1' => 'required',
            // '2' => 'required',
            // '3' => 'required|numeric',
            // '4' => 'required|numeric',
            // '5' => 'required|numeric',
            // '6' => 'required|numeric',
            // '7' => 'required|numeric',
            // '8' => 'required|numeric',
            // '9' => 'required|numeric',
        ];
    }

    // headers

}

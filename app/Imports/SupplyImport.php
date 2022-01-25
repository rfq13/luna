<?php

namespace App\Imports;

use Auth;
use App\Supply;
use App\Product;
use App\Supplier;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithValidation;

class SupplyImport implements ToModel, WithValidation
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */

    use Importable;

    public function model(array $row)
    {
        $product = Product::where('kode_barang', $row[0])
            ->first();
        $product->stok += $row[1];

        if ($product->stok > 0) {
            $product->keterangan = 'Tersedia';
        }
        $product->save();
        // $product = Product::where('kode_barang', $row[0])
        // ->select('products.*')
        // ->first();
        $supplier = Supplier::select("id")->where("name", $row[3])->first();
        return new Supply([
            'product_id'     => $product->id,
            'jumlah'    => $row[1],
            'harga_beli'    => $row[2],
            'supplier_id'    => $supplier ? $supplier->id : 0,
            'ppn'    => $row[4] ?? 0,
        ]);
    }

    public function rules(): array
    {
        return [
            '0' => function ($attribute, $value, $onFailure) {
                if (Product::where('kode_barang', '=', $value)->count() == 0) {
                    $onFailure('tidak tersedia');
                } elseif ($value == null || $value == '') {
                    $onFailure('kosong');
                } elseif ($value == 0) {
                    $onFailure('nol');
                }
            },
            '1' => 'required|numeric',
            '2' => 'required|numeric',
        ];
    }
}

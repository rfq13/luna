<?php

namespace App\Http\Controllers;

use PDF;
use Session;
use App\Acces;
use App\Market;
use App\Product;
use App\Activity;
use App\Helpers\Stock;
use App\Transaction;
use App\Supply_system;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionManageController extends Controller
{
    // Show View Transaction
    public function viewTransaction()
    {
        $id_account = Auth::id();
        $check_access = Acces::where('user', $id_account)
            ->first();
        if ($check_access->transaksi == 1) {
            $products = Product::all()
                ->sortBy('kode_barang');
            $supply_system = Supply_system::first();

            return view('transaction.transaction', compact('products', 'supply_system'));
        } else {
            return back();
        }
    }

    // Take Transaction Product
    public function transactionProduct($id)
    {
        $id_account = Auth::id();
        $check_access = Acces::where('user', $id_account)
            ->first();
        if ($check_access->transaksi == 1) {
            $product = Product::where('kode_barang', '=', $id)
                ->first();
            $supply_system = Supply_system::first();
            $status = $supply_system->status;

            return response()->json([
                'product' => $product,
                'status' => $status
            ]);
        } else {
            return back();
        }
    }

    // Check Transaction Product
    public function transactionProductCheck($id)
    {
        $id_account = Auth::id();
        $check_access = Acces::where('user', $id_account)
            ->first();
        if ($check_access->transaksi == 1) {
            $product_check = Product::where('kode_barang', '=', $id)
                ->count();

            if ($product_check != 0) {
                $product = Product::where('kode_barang', '=', $id)
                    ->first();
                $supply_system = Supply_system::first();
                $status = $supply_system->status;
                $check = "tersedia";
            } else {
                $product = '';
                $status = '';
                $check = "tidak tersedia";
            }

            return response()->json([
                'product' => $product,
                'status' => $status,
                'check' => $check
            ]);
        } else {
            return back();
        }
    }

    // Transaction Process
    public function transactionProcess(Request $req)
    {
        $auth = Auth::user();
        $id_account = $auth->id;
        $check_access = Acces::where('user', $id_account)
            ->first();
        $processed = [];
        if ($check_access->transaksi == 1) {
            $jml_barang = count($req->kode_barang);
            for ($i = 0; $i < $jml_barang; $i++) {
                $transaction = new Transaction;
                $transaction->kode_transaksi = $req->kode_transaksi;
                $transaction->kode_barang = $req->kode_barang[$i];
                $product_data = Product::where('kode_barang', $req->kode_barang[$i])
                    ->first();
                $transaction->nama_barang = $product_data->nama_barang;
                $transaction->harga = $product_data->harga;
                $transaction->jumlah = $req->jumlah_barang[$i];
                $transaction->total_barang = $req->total_barang[$i];
                $transaction->subtotal = $req->subtotal;
                $transaction->diskon = $req->diskon;
                $transaction->total = $req->total;
                $transaction->bayar = $req->bayar;
                $transaction->kembali = $req->bayar - $req->total;
                $transaction->id_kasir = $id_account;
                $transaction->kasir = $auth->nama;
                $transaction->save();

                $processed[$i] = $transaction->id;
            }

            $check_supply_system = Supply_system::first();
            if ($check_supply_system->status == true) {
                for ($j = 0; $j < $jml_barang; $j++) {
                    $product = Product::select("id")
                        ->where('kode_barang', '=', $req->kode_barang[$j])
                        ->first();

                    $reduceStock = Stock::reduceStock($product->id, $req->jumlah_barang[$j],$processed[$j],$auth->branch_id);

                    if (!$reduceStock) {
                        dd(Stock::errors());
                    }

                    $product_stok = Stock::qty($product->id);
                    if ($product_stok <= 0) {
                        $product->keterangan = 'Habis';
                        $product->save();
                    }
                }
            }

            $activity = new Activity;
            $activity->id_user = $id_account;
            $activity->user = $auth->nama;
            $activity->nama_kegiatan = 'transaksi';
            $activity->jumlah = $jml_barang;
            $activity->save();

            Session::flash('transaction_success', $req->kode_transaksi);

            return back();
        } else {
            return back();
        }
    }

    // Transaction Receipt
    public function receiptTransaction($id)
    {
        $market = Market::first();
        $id_account = Auth::id();
        $check_access = Acces::where('user', $id_account)
            ->first();
        if ($check_access->transaksi == 1) {
            $transaction = Transaction::where('transactions.kode_transaksi', '=', $id)
                ->select('transactions.*')
                ->first();
            $transactions = Transaction::where('transactions.kode_transaksi', '=', $id)
                ->select('transactions.*')
                ->get();
            $diskon = $transaction->subtotal * $transaction->diskon / 100;

            $customPaper = array(0, 0, 400.00, 283.80);
            $pdf = PDF::loadview('transaction.receipt_transaction', compact('transaction', 'transactions', 'diskon', 'market'))->setPaper($customPaper, 'landscape');
            return $pdf->stream();
        } else {
            return back();
        }
    }
}

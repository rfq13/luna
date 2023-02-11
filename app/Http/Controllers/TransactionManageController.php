<?php

namespace App\Http\Controllers;

use PDF;
use Session;
use App\Acces;
use App\Market;
use App\Product;
use App\Activity;
use App\Customer;
use App\Helpers\Stock;
use App\Kredit;
use App\Transaction;
use App\Supply_system;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session as FacadesSession;

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
        $req->validate([
            // "tipe_konsumen" => "required",
            // tipe konsumen yang valid hanya ecer, grosir, khusus, dan extra
            "tipe_customer" => "required|in:ecer,grosir,khusus,extra",
            "nama_customer" => "required",
            "alamat_customer" => "required",
            "nik_customer" => "required",
            "nohp_customer" => "required",
            "npwp_customer" => "required",
            "is_kredit" => "required|in:0,1",
        ]);

        try{
            DB::beginTransaction();

            $auth = Auth::user();
            $id_account = $auth->id;
            $check_access = Acces::where('user', $id_account)->first();

            $customer = Customer::where('nik', $req->nik_customer)->first();
            if (!$customer) {
                $customer = new Customer;
                $customer->nama = $req->nama_customer;
                $customer->alamat = $req->alamat_customer;
                $customer->nik = $req->nik_customer;
                $customer->nohp = $req->nohp_customer;
                $customer->npwp = $req->npwp_customer;
                $customer->type = $req->tipe_customer;
                $customer->plafon = $req->plafon;
                $customer->save();

                $activity = new Activity;
                $activity->id_user = $id_account;
                $activity->user = $auth->nama;
                $activity->nama_kegiatan = 'customer: create -> ' . $customer->id;
                $activity->jumlah = 1;
                $activity->save();
            }

            if($req->is_kredit == 1){
                $req->validate([
                    "tenor" => "required",
                    "total_dp" => "required|numeric|min:0",
                ]);


                if($req->total > $customer->sisa_plafon){
                    return Redirect::back()->withErrors(['msg' => "Plafon tidak mencukupi $req->total > $customer->sisa_plafon"]);
                }

                $date = Carbon::now();
                switch ($req->tenor_unit) {
                    case "minggu":
                        $date->addWeek($req->tenor);
                        break;
                    case "bulan":
                        $date->addMonth($req->tenor);
                        break;
                    case "tahun":
                        $date->addYear($req->tenor);
                        break;
                    default:
                        $date->addWeek($req->tenor);
                        break;
                }

                $kredit = new Kredit;
                $kredit->customer_id = $customer->id;
                $kredit->transaction_code = $req->kode_transaksi;
                $kredit->description = "Kredit -> ".$req->kode_transaksi;
                $kredit->amount = $req->total;
                $kredit->remaining_installment = $req->total;
                $kredit->tenor = $req->tenor;
                $kredit->tenor_unit = $req->tenor_unit;
                $kredit->dp = $req->total_dp;
                $kredit->due_date = $date->format('Y-m-d H:i:s');
                $kredit->save();
            }

            $processed = [];
            if ($check_access->transaksi == 1) {
                $jml_barang = count($req->kode_barang);
                for ($i = 0; $i < $jml_barang; $i++) {
                    $transaction = new Transaction;
                    $transaction->kode_transaksi = $req->kode_transaksi;
                    $transaction->kode_barang = $req->kode_barang[$i];

                    if($req->has('ppn')){
                        $transaction->ppn = $req->ppn;
                    }

                    $product_data = Product::where('kode_barang', $req->kode_barang[$i])->first();

                    $transaction->nama_barang = $product_data->nama_barang;
                    $transaction->harga = $product_data["harga_$req->tipe_customer"];
                    $transaction->jumlah = $req->jumlah_barang[$i];
                    $transaction->total_barang = $req->total_barang[$i];
                    $transaction->subtotal = $req->subtotal;
                    $transaction->diskon = $req->diskon;
                    $transaction->total = $req->total;
                    $transaction->bayar = $req->bayar;
                    $transaction->kembali = $req->bayar - $req->total;
                    $transaction->id_kasir = $id_account;
                    $transaction->kasir = $auth->nama;
                    $transaction->tipe_customer = $req->tipe_customer;
                    $transaction->is_kredit = $req->is_kredit;
                    $transaction->customer_id = $customer->id;
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

                        if(count(Stock::errors())){
                            $errors = Stock::errors();

                            // join errors with whitespace
                            $errors = implode('\n ->', $errors);
                            throw new \Exception($errors);
                        }
                    }
                }

                $activity = new Activity;
                $activity->id_user = $id_account;
                $activity->user = $auth->nama;
                $activity->nama_kegiatan = 'transaksi';
                $activity->jumlah = $jml_barang;
                $activity->save();

                FacadesSession::flash('transaction_success', $req->kode_transaksi);

                DB::commit();
                return back();
            } else {
                return back();
            }
        }catch(\Exception $e){
            throw $e;
            FacadesSession::flash('transaction_failed', $req->kode_transaksi);
            DB::rollback();
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

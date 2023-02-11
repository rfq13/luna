<?php

namespace App\Http\Controllers;

use Session;
use Carbon\Carbon;
use App\Market;
use App\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session as FacadesSession;

class ViewManageController extends Controller
{
    // Show View Dashboard
    public function viewDashboard()
    {
    	$kd_transaction = Transaction::select('kode_transaksi','created_at')
    	->latest()
    	->distinct()
    	->take(5)
    	->get();
        $transactions = Transaction::all();
        $array = array();
        foreach ($transactions as $no => $transaction) {
            array_push($array, $transactions[$no]->created_at->toDateString());
        }
        $dates = array_unique($array);
        rsort($dates);

        $arr_ammount = count($dates);
        $incomes_data = array();
        if($arr_ammount > 7){
            for ($i = 0; $i < 7; $i++) {
                array_push($incomes_data, $dates[$i]);
            }
        }elseif($arr_ammount > 0){
            for ($i = 0; $i < $arr_ammount; $i++) {
                array_push($incomes_data, $dates[$i]);
            }
        }
        $incomes = array_reverse($incomes_data);
        $kode_transaksi_dis = Transaction::select('kode_transaksi','created_at')
        ->distinct()
        ->get();
        $kode_transaksi_dis_daily = Transaction::whereDate('created_at', Carbon::now())
        ->select('kode_transaksi','created_at')
        ->distinct()
        ->get();
        $all_incomes = 0;
        $incomes_daily = 0;
        foreach ($kode_transaksi_dis as $kode) {
            $transaksi = Transaction::where('kode_transaksi', $kode->kode_transaksi)->first();
            $all_incomes += $transaksi->total;
        }
        foreach ($kode_transaksi_dis_daily as $kode) {
            $transaksi_daily = Transaction::where('kode_transaksi', $kode->kode_transaksi)->first();
            $incomes_daily += $transaksi_daily->total;
        }
        $customers_daily = count($kode_transaksi_dis_daily);
        $min_date = Transaction::min('created_at');
        $max_date = Transaction::max('created_at');
        $market = Market::first();

    	return view('dashboard', compact('kd_transaction', 'incomes', 'incomes_daily', 'customers_daily', 'all_incomes', 'min_date', 'max_date', 'market'));
    }

    // Filter Chart Dashboard
    public function filterChartDashboard($filter)
    {
        if($filter == 'pemasukan'){
            $supply_products = Transaction::all();
            $array = array();
            foreach ($supply_products as $no => $supply) {
                array_push($array, $supply_products[$no]->created_at->toDateString());
            }
            $dates = array_unique($array);
            rsort($dates);
            $arr_ammount = count($dates);
            $incomes_data = array();
            if($arr_ammount > 7){
                for ($i = 0; $i < 7; $i++) {
                    array_push($incomes_data, $dates[$i]);
                }
            }elseif($arr_ammount > 0){
                for ($i = 0; $i < $arr_ammount; $i++) {
                    array_push($incomes_data, $dates[$i]);
                }
            }
            $incomes = array_reverse($incomes_data);
            $total = array();
            foreach ($incomes as $no => $income) {
                array_push($total, Transaction::whereDate('created_at', $income)->sum('total'));
            }

            return response()->json([
                'incomes' => $incomes,
                'total' => $total
            ]);
        }else{
            $supply_products = Transaction::all();
            $array = array();
            foreach ($supply_products as $no => $supply) {
                array_push($array, $supply_products[$no]->created_at->toDateString());
            }
            $dates = array_unique($array);
            rsort($dates);
            $arr_ammount = count($dates);
            $customer_data = array();
            if($arr_ammount > 7){
                for ($i = 0; $i < 7; $i++) {
                    array_push($customer_data, $dates[$i]);
                }
            }elseif($arr_ammount > 0){
                for ($i = 0; $i < $arr_ammount; $i++) {
                    array_push($customer_data, $dates[$i]);
                }
            }
            $customers = array_reverse($customer_data);
            $jumlah = array();
            foreach ($customers as $no => $customer) {
                array_push($jumlah, Transaction::whereDate('created_at', $customer)->count());
            }

            return response()->json([
                'customers' => $customers,
                'jumlah' => $jumlah
            ]);
        }
    }

    // Update Market
    public function updateMarket(Request $req)
    {
        $market = Market::first();
        $market->nama_toko = $req->nama_toko;
        $market->no_telp = $req->no_telp;
        $market->alamat = $req->alamat;
        $market->save();

        FacadesSession::flash('update_success', 'Pengaturan berhasil diubah');

        return back();
    }
}

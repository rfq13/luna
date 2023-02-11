<?php

namespace App\Http\Controllers;

use PDF;
use App\Acces;
use App\SupplyProduct;
use App\Market;
use App\Product;
use App\Activity;
use App\Helpers\GeneralHelper;
use Carbon\Carbon;
use App\Supply_system;
use Illuminate\Http\Request;
use App\Imports\SupplyImport;
use App\Supply;
use App\SupplyHistory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class SupplyManageController extends Controller
{
    // Supply System
    public function supplySystem($id,$id_account = 0, $prevent = false)
    {
        if (!$id_account) {
            $id_account = Auth::id();
        }

        $check_access = Acces::where('user', $id_account)
            ->first();
        if ($check_access->kelola_barang == 1) {
            $supply_system = Supply_system::first();
            if ($id == 'active') {
                $supply_system->status = true;
                $supply_system->save();

                if (!$prevent) {
                    session()->flash('supply_system_status', 'Sistem berhasil diaktifkan');

                    return back();
                }
            } else {
                $supply_system->status = false;
                $supply_system->save();

                if (!$prevent) {
                    session()->flash('supply_system_status', 'Sistem berhasil dinonaktifkan');

                    return back();
                }
            }
            return true;
        } else {
            return back();
        }
    }

    // Show View Supply
    public function viewSupply()
    {

        $id_account = Auth::id();
        $check_access = Acces::where('user', $id_account)
            ->first();
        $supply_system = Supply_system::first();
        if ($check_access->kelola_barang == 1 && $supply_system->status == true) {
            $chunk_by_date = Supply::whereHas('supply_product')->get()->groupBy(function ($item, $key) {
                return $item->created_at->format('d-m-Y');
            });

            return view('manage_product.supply_product.supply', compact('chunk_by_date'));
        } else {
            return back();
        }
    }

    // Show View Statistic Supply
    public function statisticsSupply()
    {
        $id_account = Auth::id();
        $check_access = Acces::where('user', $id_account)->first();
        $supply_system = Supply_system::first();
        if ($check_access->kelola_barang == 1 && $supply_system->status == true) {
            $products = Product::all()->sortBy('kode_barang');

            return view('manage_product.supply_product.statistics_supply', compact('products'));
        } else {
            return back();
        }
    }

    // Show View New Supply
    public function viewNewSupply()
    {
        $id_account = Auth::id();
        $check_access = Acces::where('user', $id_account)
            ->first();
        $supply_system = Supply_system::first();
        if ($check_access->kelola_barang == 1 && $supply_system->status == true) {
            $products = Product::all()
                ->sortBy('kode_barang');

            return view('manage_product.supply_product.new_supply', compact('products'));
        } else {
            return back();
        }
    }

    // Check Supply Data
    public function checkSupplyCheck($id)
    {
        $id_account = Auth::id();
        $check_access = Acces::where('user', $id_account)
            ->first();
        $supply_system = Supply_system::first();
        if ($check_access->kelola_barang == 1 && $supply_system->status == true) {
            $check_product = Product::where('kode_barang', $id)
                ->count();

            if ($check_product != 0) {
                echo "sukses";
            } else {
                echo "gagal";
            }
        } else {
            return back();
        }
    }

    // Take Supply Data
    public function checkSupplyData($id)
    {
        $id_account = Auth::id();
        $check_access = Acces::where('user', $id_account)
            ->first();
        $supply_system = Supply_system::first();
        if ($check_access->kelola_barang == 1 && $supply_system->status == true) {
            $product = Product::where('kode_barang', $id)
                ->first();

            return response()->json(['product' => $product]);
        } else {
            return back();
        }
    }

    // Take Supply Statics Product
    public function statisticsProduct($id)
    {
        $id_account = Auth::id();
        $check_access = Acces::where('user', $id_account)
            ->first();
        $supply_system = Supply_system::first();
        if ($check_access->kelola_barang == 1 && $supply_system->status == true) {
            $product = Product::where('kode_barang', '=', $id)
                ->first();
            $supply_products = SupplyHistory::where('product_id', '=', $product->id)
                // ->select('supply_products.*')
                ->orderBy('created_at', 'ASC')
                ->get();
            $dates = array();
            $ammounts = array();
            foreach ($supply_products as $no => $supply) {
                $dates[$no] = date('d M, Y', strtotime($supply->created_at));
                $ammounts[$no] = $supply->harga_beli;
            }

            return response()->json([
                'product' => $product,
                'dates' => $dates,
                'ammounts' => $ammounts
            ]);
        } else {
            return back();
        }
    }

    // Take Supply Statics Users
    public function statisticsUsers($id)
    {
        $id_account = Auth::id();
        $check_access = Acces::where('user', $id_account)
            ->first();
        $supply_system = Supply_system::first();
        if ($check_access->kelola_barang == 1 && $supply_system->status == true) {
            echo SupplyHistory::select('supplier_id')
                ->whereHas('product', function ($product) use ($id) {
                    return $product->where("kode_barang", $id);
                })
                ->distinct()
                ->count() . ' Pemasok';
        } else {
            return back();
        }
    }

    // Take Supply Statics Table
    public function statisticsTable($id)
    {
        $id_account = Auth::id();
        $check_access = Acces::where('user', $id_account)
            ->first();
        $supply_system = Supply_system::first();
        if ($check_access->kelola_barang == 1 && $supply_system->status == true) {
            $supply_products = SupplyHistory::whereHas('product', function ($product) use ($id) {
                return $product->where('kode_barang', $id);
            })
                ->with("supply.supplier")
                ->whereHas("supply.supplier")
                ->select('supply_histories.*')
                ->orderBy('created_at', 'DESC')
                ->get();

            return view('manage_product.supply_product.statistics_table', compact('supplies'));
        } else {
            return back();
        }
    }

    // Create New Supply
    public function createSupply(Request $req)
    {

        try {
            DB::beginTransaction();
            $id_account = Auth::id();
            $check_access = Acces::where('user', $id_account)
                ->first();
            $supply_system = Supply_system::first();
            if ($check_access->kelola_barang == 1 && $supply_system->status == true) {
                $jumlah_data = 0;

                $supply = new Supply;
                $supply->kode_supply = GeneralHelper::generateCode();
                $supply->supplier_id = $req->supplier_id;
                $supply->total_harga = array_sum($req->harga_beli_supply);
                $supply->save();

                foreach ($req->kode_barang_supply as $no => $kode_barang) {
                    $product = Product::where('kode_barang', $kode_barang)
                        ->first();

                    if ($product->stok > 0) {
                        $product->keterangan = 'Tersedia';
                    }
                    $product->save();

                    $supply_product = new SupplyProduct;

                    $supply_product->jumlah = $req->jumlah_supply[$no];
                    $supply_product->harga_beli = $req->harga_beli_supply[$no];
                    $supply_product->supply_id = $supply->id;
                    // $supply_product->supplier_id = $req->supplier_id[$no];
                    $supply_product->product_id = $product->id;
                    $supply_product->ppn = $req->ppn[$no] ?? 0;
                    $supply_product->save();
                    $jumlah_data += 1;
                }

                $activity = new Activity;
                $activity->id_user = Auth::id();
                $activity->user = Auth::user()->nama;
                $activity->nama_kegiatan = 'pasok';
                $activity->jumlah = $jumlah_data;
                $activity->save();

                DB::commit();

                session()->flash('create_success', 'Barang berhasil dipasok');

                return redirect('/supply');
            } else {
                return back();
            }

        } catch (\Throwable $th) {
            throw $th;

            session()->flash('create_failed', 'Barang gagal dipasok');
            return back()->withInput();
        }
    }

    // Import New Supply
    public function importSupply(Request $req)
    {
        try {
            $id_account = Auth::id();
            $check_access = Acces::where('user', $id_account)->first();
            $supply_system = Supply_system::first();

            if ($check_access->kelola_barang == 1 && $supply_system->status == true) {
                DB::beginTransaction();

                $file = $req->file('excel_file');
                $nama_file = rand() . $file->getClientOriginalName();
                $file->move('public/excel_file', $nama_file);

                $supply = new Supply;
                $supply->kode_supply = GeneralHelper::generateCode();
                $supply->supplier_id = $req->supplier_id;
                $supply->total_harga = 0;
                $supply->is_import = true;
                $supply->save();

                $import = new SupplyImport($supply);

                Excel::import($import, public_path('/excel_file/' . $nama_file));

                $array = $import->toArray(public_path('/excel_file/' . $nama_file));

                $jumlah_data = count($array[0]);

                $activity = new Activity;
                $activity->id_user = Auth::id();
                $activity->user = Auth::user()->nama;
                $activity->nama_kegiatan = 'pasok';
                $activity->jumlah = $jumlah_data;
                $activity->save();

                session()->flash('import_success', 'Data barang berhasil diimport');
                DB::commit();

                return redirect('/supply');
            } else {
                return back();
            }
        } catch (\Exception $ex) {
            dd("jamiln", $ex->getMessage());
            throw $ex;
            session()->flash('import_failed', 'Cek kembali terdapat data kosong, stok barang kosong atau kode barang yang tidak tersedia');

            return back();
        }
    }

    // Export Supply Report
    public function exportSupply(Request $req)
    {
        $id_account = Auth::id();
        $check_access = Acces::where('user', $id_account)
            ->first();
        $supply_system = Supply_system::first();
        if ($check_access->kelola_barang == 1 && $supply_system->status == true) {
            $jenis_laporan = $req->jns_laporan;
            $current_time = Carbon::now()->isoFormat('Y-MM-DD') . ' 23:59:59';
            if ($jenis_laporan == 'period') {
                if ($req->period == 'minggu') {
                    $last_time = Carbon::now()->subWeeks($req->time)->isoFormat('Y-MM-DD') . ' 00:00:00';
                    $supply_products = SupplyProduct::select('supply_products.*')
                        ->whereBetween('created_at', array($last_time, $current_time))
                        ->get();
                    $array = array();
                    foreach ($supply_products as $no => $supply) {
                        array_push($array, $supply_products[$no]->created_at->toDateString());
                    }
                    $dates = array_unique($array);
                    rsort($dates);
                    $tgl_awal = $last_time;
                    $tgl_akhir = $current_time;
                } elseif ($req->period == 'bulan') {
                    $last_time = Carbon::now()->subMonths($req->time)->isoFormat('Y-MM-DD') . ' 00:00:00';
                    $supply_products = SupplyProduct::select('supply_products.*')
                        ->whereBetween('created_at', array($last_time, $current_time))
                        ->get();
                    $array = array();
                    foreach ($supply_products as $no => $supply) {
                        array_push($array, $supply_products[$no]->created_at->toDateString());
                    }
                    $dates = array_unique($array);
                    rsort($dates);
                    $tgl_awal = $last_time;
                    $tgl_akhir = $current_time;
                } elseif ($req->period == 'tahun') {
                    $last_time = Carbon::now()->subYears($req->time)->isoFormat('Y-MM-DD') . ' 00:00:00';
                    $supply_products = SupplyProduct::select('supply_products.*')
                        ->whereBetween('created_at', array($last_time, $current_time))
                        ->get();
                    $array = array();
                    foreach ($supply_products as $no => $supply) {
                        array_push($array, $supply_products[$no]->created_at->toDateString());
                    }
                    $dates = array_unique($array);
                    rsort($dates);
                    $tgl_awal = $last_time;
                    $tgl_akhir = $current_time;
                }
            } else {
                $start_date = $req->tgl_awal_export;
                $end_date = $req->tgl_akhir_export;
                $start_date2 = $start_date[6] . $start_date[7] . $start_date[8] . $start_date[9] . '-' . $start_date[3] . $start_date[4] . '-' . $start_date[0] . $start_date[1] . ' 00:00:00';
                $end_date2 = $end_date[6] . $end_date[7] . $end_date[8] . $end_date[9] . '-' . $end_date[3] . $end_date[4] . '-' . $end_date[0] . $end_date[1] . ' 23:59:59';
                $supply_products = SupplyProduct::select('supply_products.*')
                    ->whereBetween('created_at', array($start_date2, $end_date2))
                    ->get();
                $array = array();
                foreach ($supply_products as $no => $supply) {
                    array_push($array, $supply_products[$no]->created_at->toDateString());
                }
                $dates = array_unique($array);
                rsort($dates);
                $tgl_awal = $start_date2;
                $tgl_akhir = $end_date2;
            }
            $market = Market::first();

            $pdf = PDF::loadview('manage_product.supply_product.export_report_supply', compact('dates', 'tgl_awal', 'tgl_akhir', 'market'));
            return $pdf->stream();
        } else {
            return back();
        }
    }
}

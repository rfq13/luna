<?php

namespace App\Http\Controllers;

use App\Acces;
use App\Branch;
use App\Distribution;
use App\Helpers\Stock;
use App\Product;
use App\Supply_system;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DistributionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        $id_account = Auth::id();
        $check_access = Acces::select('kelola_barang')->where('user', $id_account)->first();

        if ($check_access->kelola_barang == 1) {
            $products = Product::with("satuan")->orderBy("kode_barang", "desc")->get();
            $supply_system = Supply_system::first();

            return view('manage_product.distribution.index', compact('products', 'supply_system'));
        } else {
            return back();
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $products = Product::all()->sortBy('kode_barang');
        $supply_system = Supply_system::first();
        $branches = Branch::get();
        return view('manage_product.distribution.create_distribution',compact('products','supply_system','branches'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->all());

        DB::beginTransaction();
        try {
            $distribution = new Distribution();
            $distribution->from = 0;
            $distribution->to = $request->branch_id;
            $distribution->kode_distribusi = $request->kode_distribusi;
            $distribution->save();

            $failed = [];

            for ($i=0; $i < count($request->id_barang); $i++) { 
                $id_barang = $request->id_barang[$i];
                $jumlah = $request->jumlah_barang[$i];

                if (Stock::qty($id_barang) >= $jumlah) {
                    Stock::transferToBranch($id_barang,$jumlah,$request->branch_id);
                }else{
                    $failed[] = $id_barang;
                }
            }

            if (count($failed) > 0) {
                dd($failed);
            }else{
                DB::commit();
                
                return redirect()->back();
            }
        } catch (\Throwable $th) {
            dd(["line"=>$th->getLine(),"message"=>$th->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Distribution  $distribution
     * @return \Illuminate\Http\Response
     */
    public function show(Distribution $distribution)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Distribution  $distribution
     * @return \Illuminate\Http\Response
     */
    public function edit(Distribution $distribution)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Distribution  $distribution
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Distribution $distribution)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Distribution  $distribution
     * @return \Illuminate\Http\Response
     */
    public function destroy(Distribution $distribution)
    {
        //
    }
}

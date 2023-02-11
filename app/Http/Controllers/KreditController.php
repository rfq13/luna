<?php

namespace App\Http\Controllers;

use App\Kredit;
use App\Repayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class KreditController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $kredits = Kredit::all();
        return view('kredit.kredit', compact('kredits'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        return Kredit::findOrFail($id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $req, $id)
    {
        $req->validate([
            "repayment_amount" => "required|numeric",
            "repayment_code" => "required",
        ]);

        try{
            DB::beginTransaction();
            $kredit = Kredit::findOrFail($id);

            $kredit->remaining_installment -= $req->repayment_amount;
            $kredit->save();

            $repayment = new Repayment;
            $repayment->kode_pelunasan = $req->repayment_code;
            $repayment->nominal = $req->repayment_amount;
            $repayment->customer_id = $kredit->customer_id;
            $repayment->kredit_id = $kredit->id;
            $repayment->user_id = Auth::id();
            $repayment->keterangan = $req->repayment_description;
            $repayment->save();

            DB::commit();
            return back(201)->with('update_success', 'Berhasil Membayar Cicilan');
        }catch(\Exception $e){
            return back()->withErrors(['msg' => $e->getMessage()]);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    // Filter Product Table
    public function filterTable($id)
    {
        // $id_account = Auth::id();
        // $check_access = Acces::where('user', $id_account)
        //     ->first();
        // if ($check_access->kelola_barang == 1) {
        //     $supply_system = Supply_system::first();
        //     $products = Product::orderBy($id, 'asc')
        //         ->get();

        //     return view('manage_product.filter_table.table_view', compact('products', 'supply_system'));
        // } else {
        //     return back();
        // }
    }
}

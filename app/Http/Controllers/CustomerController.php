<?php

namespace App\Http\Controllers;

use App\Activity;
use App\Customer;
use App\Repayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
    public function update(Request $request, $id)
    {
        try{
            DB::beginTransaction();
            $updateData = [];

            if($request->has('nik')){
                $updateData['nik'] = $request->nik;
            }

            if($request->has('nama')){
                $updateData['nama'] = $request->nama;
            }

            if($request->has('alamat')){
                $updateData['alamat'] = $request->alamat;
            }

            if($request->has('nohp')){
                $updateData['nohp'] = $request->nohp;
            }

            if($request->has('plafon')){
                $updateData['plafon'] = $request->plafon;
            }

            if($request->has('status')){
                $updateData['status'] = $request->status;
            }

            // if updateData is not empty
            if(!empty($updateData)){

                $updateData['updated_at'] = date('Y-m-d H:i:s');
                Customer::where('id', $id)->update($updateData);

                $auth = Auth::user();

                $activity = new Activity;
                $activity->id_user = $auth->id;
                $activity->user = $auth->nama;
                $activity->nama_kegiatan = 'customer: edit customer -> ' . $id;
                $activity->jumlah = 1;
                $activity->save();
            }

            DB::commit();

            return response()->json([
                'data' => $updateData,
                'status' => true,
                'message' => 'Data berhasil diupdate'
            ]);
        }catch(\Exception $e){
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Data gagal diupdate',
                'message' => $e->getMessage()
            ]);
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

    // searchByNik
    public function searchByNik(Request $request, $nik)
    {
        $customer = Customer::where('nik', $nik)->first();

        return response()->json([
            'customer' => $customer
        ]);
    }
}

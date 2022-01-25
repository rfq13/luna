<?php

namespace App\Http\Controllers;

use App\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view("supplier.main");
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
     * @param  \App\Supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function show(Supplier $supplier)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function edit(Supplier $supplier, $id)
    {
        return $supplier->find($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Supplier $supplier)
    {
        $supplier = $supplier->findOrNew($request->id);
        $supplier->name = $request->name;
        $supplier->save();
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function delete(Supplier $supplier, $id)
    {
        if ($supplier->find($id)->delete()) {
            Session::flash("delete_success", "berhasil menghapus supplier");
        }
        return back();
    }

    public function new(Request $req)
    {
        dd("hogeh");
    }

    function data(Request $req, $filter = 'name')
    {
        if ($filter == 'total') {
            return [];
        }

        $sort = $filter == 'name' ? 'asc' : 'desc';
        return Supplier::with('products')->orderBy($filter, $sort)->get();
    }
}

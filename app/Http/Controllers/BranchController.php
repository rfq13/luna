<?php

namespace App\Http\Controllers;

use App\Branch;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BranchController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $admins = User::where("branch_id", "!=", 0)
            ->where("role", "admin")
            ->with("branch")
            ->whereHas("branch")
            ->get();

        return view("branch.main", compact("admins"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $admins  = DB::select("SELECT * FROM users WHERE branch_id = 0 AND role = 'admin' AND id != (SELECT id FROM users WHERE username = 'admin') LIMIT 1");
        // dd($admins);
        return view("branch.create", compact("admins"));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $branch = new Branch;
            $branch->name = $request->branch_name;
            $branch->address = $request->address;
            $branch->save();

            if ($request->branch_admin) {
                $adminId = $request->branch_admin;
                User::find($adminId)->update(['branch_id' => $branch->id]);
            } else {
                $request->request->add(['role' => 'admin']);
                $newUser = (new UserManageController)->createAccount($request, true);
                $newUser->branch_id = $branch->id;
                $newUser->save();
            }

            DB::commit();
            session()->flash("create_success", "berhasil membuat cabang baru");
            return redirect("/branch");
        } catch (\Throwable $th) {
            dd($th->getMessage());
            session()->flash("both_error", "ada kesalahan pada server");
            return back()->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Branch  $branch
     * @return \Illuminate\Http\Response
     */
    public function show(Branch $branch)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Branch  $branch
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        $branch = Branch::find($id);
        $admins = User::select("id", "nama", "branch_id")->whereIn('branch_id', [0, $id])->where("role", "admin")->get();
        return compact("branch", "admins");
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Branch  $branch
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $branch = Branch::find($request->id);
        $branch->name = $request->name;
        $branch->address = $request->address;
        $branch->save();

        User::where(['role' => 'admin', 'branch_id' => $branch->id])->update(['branch_id' => 0]);
        User::where(['role' => 'admin', 'branch_id' => 0, 'id' => $request->branch_admin])->update(['branch_id' => $branch->id]);

        session()->flash("update_success", "berhasil merubah data cabang");
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Branch  $branch
     * @return \Illuminate\Http\Response
     */
    public function destroy(Branch $branch, Request $request)
    {
        DB::beginTransaction();
        try {
            $id = $request->id;
            $branch->destroy($id);
            User::where(['branch_id' => $id])->update(['branch_id' => 0]);

            DB::commit();

            session()->flash("delete_success", "berhasil menghapus cabang");
            return back();
        } catch (\Throwable $th) {
            dd($th->getMessage());
            return back()->withInput();
        }
    }
}

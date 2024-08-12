<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\PermissionGroup;
use App\Models\PermissionPermissionGroup;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{

    function __construct()
    {
        // $this->middleware('permission:permissions.view');
        // $this->middleware('permission:permissions.create', ['only' => ['create', 'store']]);
        // $this->middleware('permission:permissions.edit', ['only' => ['edit', 'update']]);
        // $this->middleware('permission:permissions.delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $permissions = Permission::get();
        return view('permissions.index', compact('permissions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $Permissions_group = PermissionGroup::get();
        // dd($Permissions_group);
        return view('permissions.create',compact('Permissions_group'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        request()->validate([
            'name' => 'required',
            'display_name' => 'required',
        ]);

      $permission=  Permission::create([
        'name'   =>$request->name,
        'guard_name'   =>'web',
        'display_name'    =>$request->display_name,
      ]);
      PermissionPermissionGroup::insert([
        'permission_id'     =>$permission->id,
        'permission_group_id'   =>$request->group
      ]);
        return redirect()->route('permissions.create')
            ->with('success', 'Permission Inserted SuccessFully');
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

    public function edit(Permission $permission)
    {

        $Permissions_group  = PermissionGroup::with('permissions')->get();
        return view('permissions.create', compact('permission','Permissions_group'));
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Permission $permission)
    {
        request()->validate([
            'name' => 'required',
        ]);
        $permission->update($request->all());
        return redirect()->route('permissions.index')
            ->with('success', 'SuccessFully Updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Permission $permission)
    {
        $permission->delete();
        return redirect()->route('permissions.index')
            ->with('success', ' deleted successfully');
    }
}

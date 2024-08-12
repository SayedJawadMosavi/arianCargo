<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\Branch;
use Illuminate\Http\Request;
use App\Models\User;
use Hash;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:users.view');
        $this->middleware('permission:users.create', ['only' => ['create', 'store']]);
        $this->middleware('permission:users.edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:users.delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(auth()->user()->branch_id == 1){
            $user = User::with('branch')->get();
        }else{
            $user = User::with('branch')->branch()->get();
        }

        return view('user.index')->with('users', $user );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::all();
        if(auth()->user()->branch_id == 1){
            $branches = Branch::all();
        }else{
            $branches = Branch::where('id',auth()->user()->branch_id)->get();
        }
        return view('user.create',compact('roles','branches'));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:users,name',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|same:confirm_password',
            'roles' => 'required',
            'branch_id' => 'required'
        ]);
        $image_path_el="";
        if($request->hasFile('photo')){
            $file = $request->file('photo');
            $filename = time().'_'.$file->getClientOriginalName();
            // File upload location
            $location = 'images/users/';
            // Upload file
            $image_path=   $file->move($location,$filename);
            $image_path_el = $image_path;

        }



      $user=  User::create([

            'name' => $request->name,
            'email' => $request->email,
            'branch_id' => $request->branch_id,

            'password' => Hash::make($request->password),

            'image' => $image_path_el,

        ]);
        $user->assignRole($request->input('roles'));
        return redirect()->route('users.index')->with('success', 'New user added successfully');
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
        $user=User::find($id);
        $roles = Role::all();
        $branches = Branch::all();


        $userRole = $user->roles->pluck('name', 'name')->all();
        return view('user.create')->with('users', $user)->with('branches', $branches)->with('roles', $roles)->with('userRole', $userRole);
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

        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'same:confirm_password',
            'roles' => 'required',
            'branch_id' => 'required'
        ]);
        $image_path_el="";
        if($request->hasFile('photo')){
            $file = $request->file('photo');
            $filename = time().'_'.$file->getClientOriginalName();
            // File upload location
            $location = 'images/users/';
            // Upload file
            $image_path=   $file->move($location,$filename);
            $image_path_el = $image_path;

        }
         $user = User::find($id);
         if (!empty($request->password)) {
            $password= Hash::make($request->password);
         }else{
             $password=auth()->user()->password;
         }

     User::where('id',$id)->update([

            'name' => $request->name,
            'email' => $request->email,
            'branch_id' => $request->branch_id,

            'password' => $password,

            'image' => $image_path_el,

        ]);
         \DB::table('model_has_roles')->where('model_id', $id)->delete();
        $user->assignRole($request->roles);
        return redirect()->route('users.index')->with('success', ' user updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        $flag = false;
        try {
            $user = User::findOrFail($id);

            // Delete related records
            // Assuming you have related models like posts, comments, etc.
            // $user->posts()->delete();
            // $user->comments()->delete();
            // // Add other related models here

            // Now delete the user
            $flag = $user->delete();
            if ($flag) {
                DB::commit();
                return redirect()->route('users.index')->with('success', 'User deleted successfully');
            } else {
                DB::rollBack();
                return redirect()->route('users.index')->with('error', 'User deletion failed');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            // Handle the exception
            return redirect()->back()->with('error', 'Error deleting User: ' . $e->getMessage());
        }

    }
}

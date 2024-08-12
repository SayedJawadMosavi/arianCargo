<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('auth.login');
    }

    public function showForgetPasswordForm()
    {
        return view('auth.forgot');
    }

    public function submitForgetPasswordForm(Request $request)
      {
          $request->validate([
              'email' => 'required|email|exists:users',
          ]);

          $token = Str::random(64);

          DB::table('password_resets')->insert([
              'email' => $request->email,
              'token' => $token,
              'created_at' => Carbon::now()
            ]);

          Mail::send('email.forgetPassword', ['token' => $token], function($message) use($request){
              $message->to($request->email);
              $message->subject('Reset Password');
          });

          return back()->with('success', 'We have e-mailed your password reset link!');
      }


      public function showResetPasswordForm($token) {
        return view('auth.forgetPasswordLink', ['token' => $token]);
     }

     /**
      * Write code on Method
      *
      * @return response()
      */
     public function submitResetPasswordForm(Request $request)
     {
         $request->validate([
             'email' => 'required|email|exists:users',
             'password' => 'required|string|min:3|confirmed',
             'password_confirmation' => 'required'
         ]);

         $updatePassword = DB::table('password_resets')
                             ->where([
                               'email' => $request->email,
                               'token' => $request->token
                             ])
                             ->first();

         if(!$updatePassword){
             return back()->withInput()->with('error', 'Invalid token!');
         }

         $user = User::where('email', $request->email)
                     ->update(['password' => Hash::make($request->password)]);

         DB::table('password_resets')->where(['email'=> $request->email])->delete();

         return redirect('/login')->with('message', 'Your password has been changed!');
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
        $request->validate([
            'email' => 'email|required',
            'password' => 'required'
        ]);
        $credentials = $request->only('email', 'password');

        if (!auth()->attempt($credentials)) {
            return redirect()->back()->with('error', 'نام کاربر و یا رمز اشتباه است');
        }

        $user = auth()->user();

        if (!$user->active) {
            auth()->logout(); // Log the user out if not active
            return redirect()->back()->with('error', 'Your account is not active.');
        }

        // If the user is active, log them in
        auth()->login($user, $request->remember);

        return redirect()->route('dashboard')->with('success', 'Welcome back Mr.' . $user->name);
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
        //
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

    public function getForm(){
        $user = auth()->user();
        return view('auth.profile', compact('user'));
    }
    public function saveForm(Request $request)
    {
        $user = User::find($request->user);
        if(Hash::check($request->currentPassword, $user->password)){
            $data = $request->validate([
                'password' => 'required|confirmed|min:3|max:255'
            ]);
            $user->update(['password' => bcrypt($data['password'])]);
            auth()->login($user);
            return back()->with('success', 'Passwords changed successfully!');
        }else{
            return back()->with('error', 'Passwords do not match!');
        }
    }
}

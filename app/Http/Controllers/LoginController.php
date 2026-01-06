<?php

namespace App\Http\Controllers;
use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;



class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('Admin.login'); 
    }

     

    public function login(Request $request)
    {
         $request->validate([
            'email'    => 'required|email',
            'password' => 'required'
        ]);

         
        $admin = UserModel::where('email', $request->email)->first();

         
        if (!$admin || !Hash::check($request->password, $admin->password)) {

            Log::warning('Login failed', [
                'email' => $request->email,
                'ip'    => $request->ip(),
                'time'  => now()
            ]);

            return back()
                ->withErrors(['email' => 'Invalid email or password'])
                ->withInput();
        }

         
        Log::info('Login success', [
            'admin_id' => $admin->id,
            'email'    => $admin->email,
            'ip'       => $request->ip(),
            'time'     => now()
        ]);

        
        $request->session()->put('admin_id', $admin->id);
        $request->session()->put('admin_name', $admin->name);
        $request->session()->put('role_id', $admin->role_id);

        return redirect('/admin/dashboard');




        // $request->validate([
        //     'email' => 'required|email',
        //     'password' => 'required',
        // ]);

        // // Get admin by email
        // $admin = UserModel::where('email', $request->email)->first();

      
        // if (!$admin || $request->password !== $admin->password) {
            
        //     Log::warning('Admin login failed', [
        //         'email' => $request->email,
        //         'ip' => $request->ip(),
        //         'time' => now()
        //     ]);

        //     return back()->withErrors(['email' => 'Invalid credentials'])->withInput();
        // }

       
        // Log::info('Admin logged in successfully', [
        //     'admin_id' => $admin->id,
        //     'email' => $admin->email,
        //     'ip' => $request->ip(),
        //     'time' => now()
        // ]);

        
        // $request->session()->put('admin_id', $admin->id);
        // $request->session()->put('admin_name', $admin->name);

        // return redirect('/admin/dashboard');
    }

     public function dashboard(Request $request)
    {

        if (!$request->session()->has('admin_id')) {
            return redirect('/admin/login');
        }

        return view('Admin.dashboard', [
            'admin_name' => $request->session()->get('admin_name')
        ]);
        // if (!$request->session()->has('admin_id')) {
        //     return redirect('/admin/login');
        // }

        // return view('admin.dashboard', [
        //     'admin_name' => $request->session()->get('admin_name')
        // ]);
    }

    public function logout(Request $request)
    {
        // $request->session()->flush();
        // return redirect('/admin/login');
       return view('Admin.login'); 
    }

}

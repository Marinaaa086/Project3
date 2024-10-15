<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User; 
use Illuminate\Support\Facades\Hash;  
use Illuminate\Support\Facades\Auth;  

class LoginRegisterController extends Controller
{
    
    public function login()
    {
        return view('auth.login');
    }

    
    public function postLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:8',
        ]);

        if (auth()->attempt($request->only('email', 'password'))) {
            $user = auth()->user();
            if ($user->level == 'admin') {
                return redirect('/admin/home');
            } elseif ($user->level == 'user') {
                return redirect('/user/home');
            }
        }

        return back()->with('failed', 'Email atau password salah!');
    }

    
    public function register()
    {
        return view('auth.register');
    }

   
    public function postRegister(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'jenisKelamin' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->jenis_kelamin = $request->jenisKelamin;
        $user->password = Hash::make($request->password);
        $user->save();

        return redirect('/auth/login')->with('success', 'Akun berhasil dibuat, silakan login!');
    }

    
    public function userHome()
    {
        return view('user.home');  
    }

    
    public function adminHome()
    {
        return view('admin.home');  
    }

    public function logout()
    {
        auth()->logout();
        return redirect('/');
    }
}
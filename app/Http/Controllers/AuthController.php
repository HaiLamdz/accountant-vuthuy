<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Session::has('logged_in')) {
            return redirect()->route('debt-collections.index');
        }
        
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        // Đăng nhập đơn giản - username: admin, password: 123456
        if ($request->username === 'admin' && $request->password === 'thuyvu6868') {
            Session::put('logged_in', true);
            Session::put('username', $request->username);
            
            return redirect()->route('debt-collections.index')
                ->with('success', 'Đăng nhập thành công!');
        }

        return back()
            ->withInput()
            ->with('error', 'Tên đăng nhập hoặc mật khẩu không đúng!');
    }

    public function logout()
    {
        Session::flush();
        
        return redirect()->route('login')
            ->with('success', 'Đã đăng xuất!');
    }
}

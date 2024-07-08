<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function index()
    {
        return view('auth.login', [
            'title' => 'Login',
        ]);
    }

    public function register()
    {
        return view('auth.register', [
            'title' => 'Register',
        ]);
    }

    public function lupa_password()
    {
        return view('auth.lupa_password', [
            'title' => 'Lupa Password',
        ]);
    }

    public function process_lupa_password(Request $request)
    {
        $details = [
            'title' => 'Mail from Laravel',
            'body' => 'This is for testing email using smtp'
        ];

        // Mail::to('recipient@example.com')->send(new YourMailable($details));

        return "Email has been sent!";
    }

    public function process_login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput($request->only('email'));
        }

        if (Auth::attempt($request->only('email', 'password'))) {
            $request->session()->regenerate();
            if (Auth::user()->role == 'admin') {
                return redirect()->route('admin.dashboard.index');
            } else {
                return redirect()->intended();
            }
        }

        return redirect()->back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->withInput($request->only('email'));
    }

    public function process_register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6'],
            'nama' => ['required', 'string', 'max:255'],
            'telp' => ['required', 'string', 'max:15'],
            'instansi' => ['required', 'string', 'max:255'],
            'status' => ['required', 'in:mahasiswa,dosen,umum'],
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $request['password'] = Hash::make($request->password);
        $user = User::create($request->all());

        return redirect()->route('auth.login')->with('status', 'success#Registration successful. Please login.');
    }

    public function logout()
    {
        Auth::logout();
        return redirect(url('auth/login'))->with('status', 'success#Anda telah logout.');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register()
    {
        return view('admin.pages.auth.register', [
            'title' => 'Daftar Akun',
        ]);
    }

    public function registerStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'                  => 'required|string|max:255',
            'email'                 => 'required|email|unique:users,email',
            'password'              => 'required|min:6|confirmed',
            'role'                  => 'in:student,teacher,admin', // default student
        ], [
            'name.required'         => 'Nama wajib diisi.',
            'email.required'        => 'Email wajib diisi.',
            'email.email'           => 'Format email tidak valid.',
            'email.unique'          => 'Email sudah digunakan.',
            'password.required'     => 'Kata Sandi wajib diisi.',
            'password.min'          => 'Kata Sandi minimal 6 karakter.',
            'password.confirmed'    => 'Konfirmasi Kata Sandi tidak sesuai.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => $request->role ?? 'student',
        ]);

        // login otomatis
        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('dashboard')
            ->with('success', 'Akun berhasil dibuat. Selamat datang di dashboard!');
    }
    public function login()
    {
        // dd(User::all());
        return view('admin.pages.auth.login', [
            'title' => 'Masuk Akun',
        ]);
    }
    public function authenticated(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
            'g-recaptcha-response' => 'recaptcha',
        ], [
            'email.required' => 'Email harus diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Kata Sandi harus diisi.',
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors()->all();

            return response()->json([
                'status' => 'false',
                'title' => 'Error',
                'description' => $errors[0],
                'icon' => 'error'
            ]);
        }
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            // Jika pengguna telah terverifikasi, izinkan untuk login
            $user = Auth::user();
                $request->session()->regenerate();
                return response()->json([
                    'status' => 'true',
                    'title' => 'Selamat Datang',
                    'description' => 'Sesaat lagi anda akan diarahkan ke dasbor utama.',
                    'icon' => 'success'
                ]);
        } else {
            return response()->json([
                'status' => 'false',
                'title' => 'Error',
                'description' => 'Email atau Kata Sandi salah.',
                'icon' => 'error'
            ]);
        }
    }
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();


        return redirect('login');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class TeacherController extends Controller
{
    public function index()
    {
        $teachers = User::where('role', 'teacher')->latest()->paginate(12);
        return view('admin.pages.users.teachers.index', compact('teachers'));
    }

    public function create()
    {
        return view('admin.pages.users.teachers.create');
    }

    public function store(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'min:6', 'confirmed'],
        ], [
            'name.required' => 'Nama wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email'   => 'Format email tidak valid.',
            'email.unique'  => 'Email sudah digunakan.',
            'password.required'  => 'Kata sandi wajib diisi.',
            'password.min'       => 'Minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak sama.',
        ]);

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => \Illuminate\Support\Facades\Hash::make($request->password),
            'role'     => 'teacher',
        ]);

        return redirect()->route('teachers.index')->with('success', 'Guru berhasil ditambahkan.');
    }


    public function show(User $user)
    {
        abort_unless($user->role === 'teacher', 404);
        return view('admin.pages.users.teachers.show', compact('user'));
    }

    public function destroy(User $user)
    {
        abort_unless($user->role === 'teacher', 404);

        if ($user->id === auth()->id()) {
            return back()->with('error', 'Tidak dapat menghapus akun Anda sendiri.');
        }

        $user->delete();
        return redirect()->route('teachers.index')->with('success', 'Guru berhasil dihapus.');
    }
}

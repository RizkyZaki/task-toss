<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index()
    {
        $students = User::where('role','student')->latest()->paginate(12);
        return view('admin.pages.users.students.index', compact('students'));
    }

    public function show(User $user)
    {
        abort_unless($user->role === 'student', 404);
        return view('admin.pages.users.students.show', compact('user'));
    }

    public function destroy(User $user)
    {
        abort_unless($user->role === 'student', 404);

        if ($user->id === auth()->id()) {
            return back()->with('error', 'Tidak dapat menghapus akun yang sedang login.');
        }

        $user->delete();
        return redirect()->route('students.index')->with('success', 'Siswa berhasil dihapus.');
    }
}

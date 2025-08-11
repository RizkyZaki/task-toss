<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use Illuminate\Http\Request;

class ClassEnrollmentController extends Controller
{
    public function joinByCode(Request $request)
    {
        $request->validate(['code' => ['required','string','size:6']]);

        $class = Classroom::where('code', strtoupper($request->code))
            ->where('is_active', true)
            ->first();

        if (!$class) {
            return back()->withErrors(['code' => 'Kode kelas tidak valid.']);
        }

        $class->members()->syncWithoutDetaching([auth()->id()]);

        return redirect()->route('classes.show', $class->id)
            ->with('success', 'Berhasil bergabung ke kelas.');
    }

    public function leave(Classroom $class)
    {
        $class->members()->detach(auth()->id());
        return redirect()->route('classes.index')->with('success', 'Kamu telah keluar dari kelas.');
    }

    public function index(Classroom $class)
    {
        $members = $class->members()->latest()->get();
        return view('admin.pages.classes.member', compact('class','members'));
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ClassroomController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $teaching = $user->role === 'teacher'
            ? Classroom::where('teacher_id', $user->id)->latest()->get()
            : collect();

        $enrolled = $user->classes()->latest()->get();

        return view('admin.pages.classes.index', compact('teaching', 'enrolled'));
    }

    public function create()
    {
        return view('admin.pages.classes.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $class = Classroom::create([
            'teacher_id' => auth()->id(),
            'name' => $request->name,
            'code' => strtoupper(Str::random(6)),
            'is_active' => true,
        ]);

        return redirect()->route('classes.show', $class->id)
            ->with('success', 'Kelas berhasil dibuat.');
    }

    public function show(Classroom $class)
    {
        $user = auth()->user();
        $allowed = $class->teacher_id === $user->id
            || $class->members()->where('users.id', $user->id)->exists();

        abort_if(!$allowed, 403);

        $assignments = $class->assignments()->latest()->take(10)->get();

        return view('admin.pages.classes.show', compact('class', 'assignments'));
    }

    public function edit(Classroom $class)
    {
        abort_if(auth()->id() !== $class->teacher_id, 403);
        return view('admin.pages.classes.edit', compact('class'));
    }

    public function update(Request $request, Classroom $class)
    {
        abort_if(auth()->id() !== $class->teacher_id, 403);

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $class->update([
            'name' => $request->name,
            'is_active' => (bool) $request->is_active,
        ]);

        return redirect()->route('classes.show', $class->id)
            ->with('success', 'Kelas berhasil diperbarui.');
    }

    public function destroy(Classroom $class)
    {
        abort_if(auth()->id() !== $class->teacher_id, 403);
        $class->delete();

        return redirect()->route('classes.index')
            ->with('success', 'Kelas berhasil dihapus.');
    }
}

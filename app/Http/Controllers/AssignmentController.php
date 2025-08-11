<?php
namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\Classroom;
use Illuminate\Http\Request;

class AssignmentController extends Controller
{
    public function index(Classroom $class)
    {
        $this->authorizeAccess($class);
        $assignments = $class->assignments()->latest()->paginate(10);
        return view('admin.pages.assignments.index', compact('class','assignments'));
    }

    public function create(Classroom $class)
    {
        $this->authorizeOwner($class);
        return view('admin.pages.assignments.create', compact('class'));
    }

    public function store(Request $request, Classroom $class)
    {
        $this->authorizeOwner($class);

        $request->validate([
            'title' => ['required','string','max:255'],
            'instructions' => ['nullable','string'],
            'due_at' => ['nullable','date'],
            'max_points' => ['nullable','integer','min:1','max:1000'],
            'status' => ['nullable','in:draft,published,closed'],
        ]);

        $a = $class->assignments()->create([
            'created_by' => auth()->id(),
            'title' => $request->title,
            'instructions' => $request->instructions,
            'due_at' => $request->due_at,
            'max_points' => $request->max_points ?? 100,
            'status' => $request->status ?? 'draft',
            'drive_file_id' => $request->drive_file_id,
            'link_url' => $request->link_url,
        ]);

        return redirect()->route('assignments.show', $a->id)
            ->with('success', 'Tugas dibuat.');
    }

    public function show(Assignment $assignment)
    {
        $class = $assignment->classroom;
        $this->authorizeAccess($class);
        return view('admin.pages.assignments.show', compact('assignment','class'));
    }

    public function edit(Classroom $class, Assignment $assignment)
    {
        $this->authorizeOwner($class);
        abort_if($assignment->class_id !== $class->id, 404);
        return view('admin.pages.assignments.edit', compact('assignment','class'));
    }

    public function update(Request $request, Assignment $assignment)
    {
        $class = $assignment->classroom;
        $this->authorizeOwner($class);

        $request->validate([
            'title' => ['required','string','max:255'],
            'instructions' => ['nullable','string'],
            'due_at' => ['nullable','date'],
            'max_points' => ['nullable','integer','min:1','max:1000'],
            'status' => ['nullable','in:draft,published,closed'],
        ]);

        $assignment->update($request->only('title','instructions','due_at','max_points','status','drive_file_id','link_url'));

        return redirect()->route('assignments.show', $assignment->id)
            ->with('success', 'Tugas diperbarui.');
    }

    public function destroy(Assignment $assignment)
    {
        $class = $assignment->classroom;
        $this->authorizeOwner($class);

        $assignment->delete();
        return redirect()->route('classes.show', $class->id)->with('success','Tugas dihapus.');
    }

    public function publish(Assignment $assignment)
    {
        $this->authorizeOwner($assignment->classroom);
        $assignment->update(['status' => 'published']);
        return back()->with('success','Tugas dipublish.');
    }

    public function close(Assignment $assignment)
    {
        $this->authorizeOwner($assignment->classroom);
        $assignment->update(['status' => 'closed']);
        return back()->with('success','Tugas ditutup.');
    }

    private function authorizeOwner(Classroom $class)
    {
        abort_if(auth()->id() !== $class->teacher_id, 403);
    }

    private function authorizeAccess(Classroom $class)
    {
        $u = auth()->user();
        $isTeacher = $class->teacher_id === $u->id;
        $isMember = $class->members()->where('users.id', $u->id)->exists();
        abort_if(!$isTeacher && !$isMember, 403);
    }
}

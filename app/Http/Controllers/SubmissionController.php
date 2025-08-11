<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\Classroom;
use App\Models\Submission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SubmissionController extends Controller
{
    // LIST semua submission utk 1 assignment (TEACHER)
    public function index(Assignment $assignment)
    {
        $class = $assignment->classroom;
        $this->authorizeOwner($class);

        $submissions = Submission::with(['student'])
            ->where('assignment_id', $assignment->id)
            ->latest()
            ->paginate(15);

        return view('admin.pages.submissions.index', compact('assignment', 'class', 'submissions'));
    }

    // STUDENT turn-in (buat baru)
    public function store(Request $request, Assignment $assignment)
    {
        $class = $assignment->classroom;
        $this->authorizeStudentInClass($class);

        if ($assignment->status === 'closed') {
            return back()->with('error', 'Tugas sudah ditutup.');
        }

        $request->validate([
            'answer_file' => ['required', 'file', 'max:10240', 'mimes:pdf,doc,docx,jpg,jpeg,png'],
        ]);

        // pastikan belum pernah submit
        $exists = Submission::where('assignment_id', $assignment->id)
            ->where('student_id', auth()->id())
            ->exists();

        if ($exists) {
            return back()->with('error', 'Kamu sudah mengumpulkan. Edit kirimanmu dari halaman submission.');
        }

        // Upload ke Google Drive (pakai disk 'google')
        $dir = "submissions/{$assignment->id}/" . auth()->id();
        $storedPath = Storage::disk('google')->putFile($dir, $request->file('answer_file'));
        // $storedPath contoh: "submissions/12/5/Jawaban.pdf"

        $submission = Submission::create([
            'assignment_id' => $assignment->id,
            'student_id'    => auth()->id(),
            'status'        => 'turned_in',
            'submitted_at'  => now(),
            'drive_file_id' => $storedPath, // simpan path sebagai identifier
            'link_url'      => null,
        ]);

        return redirect()->route('submissions.show', $submission->id)
            ->with('success', 'Berhasil mengumpulkan tugas.');
    }


    // ALIAS turn-in (optional route)
    public function turnIn(Request $request, Assignment $assignment)
    {
        return $this->store($request, $assignment);
    }

    // Detail submission (TEACHER pemilik kelas atau STUDENT pemilik submission)
    public function show(Submission $submission)
    {
        $class = $submission->assignment->classroom;
        $user = auth()->user();

        $isTeacher = $class->teacher_id === $user->id;
        $isOwnerStudent = $submission->student_id === $user->id;

        abort_unless($isTeacher || $isOwnerStudent, 403);

        return view('admin.pages.submissions.show', [
            'submission' => $submission->load(['student', 'assignment.classroom']),
            'class' => $class,
            'isTeacher' => $isTeacher,
            'isOwnerStudent' => $isOwnerStudent,
        ]);
    }

    // Update kiriman (STUDENT pemilik; tidak boleh jika sudah graded)
    public function update(Request $request, Submission $submission)
    {
        abort_unless($submission->student_id === auth()->id(), 403);

        if ($submission->status === 'graded') {
            return back()->with('error', 'Tidak bisa mengubah submission yang sudah dinilai.');
        }

        $request->validate([
            'answer_file' => ['nullable', 'file', 'max:10240', 'mimes:pdf,doc,docx,jpg,jpeg,png'],
        ]);

        // Jika user mengunggah file baru → hapus file lama di Drive, lalu upload yang baru
        if ($request->hasFile('answer_file')) {
            if ($submission->drive_file_id) {
                try {
                    Storage::disk('google')->delete($submission->drive_file_id);
                } catch (\Throwable $e) {
                }
            }

            $dir = "submissions/{$submission->assignment_id}/" . $submission->student_id;
            $storedPath = Storage::disk('google')->putFile($dir, $request->file('answer_file'));

            $submission->update([
                'drive_file_id' => $storedPath,
                'status'        => 'turned_in',
                'submitted_at'  => now(),
            ]);
        } else {
            $submission->update([
                'status'       => 'turned_in',
                'submitted_at' => now(),
            ]);
        }

        return back()->with('success', 'Submission diperbarui.');
    }


    // Hapus kiriman (STUDENT pemilik; tidak boleh jika sudah graded)
    public function destroy(Submission $submission)
    {
        abort_unless($submission->student_id === auth()->id(), 403);

        if ($submission->status === 'graded') {
            return back()->with('error', 'Tidak bisa menghapus submission yang sudah dinilai.');
        }

        $assignmentId = $submission->assignment_id;
        $submission->delete();

        return redirect()->route('assignments.show', $assignmentId)->with('success', 'Submission dihapus.');
    }

    // Grade (TEACHER)
    public function grade(Request $request, Submission $submission)
    {
        $class = $submission->assignment->classroom;
        $this->authorizeOwner($class);

        $request->validate([
            'score' => ['required', 'integer', 'min:0', 'max:' . $submission->assignment->max_points],
            'teacher_note' => ['nullable', 'string'],
        ]);

        $submission->update([
            'score' => $request->score,
            'teacher_note' => $request->teacher_note,
            'status' => 'graded',
        ]);

        return back()->with('success', 'Nilai tersimpan.');
    }

    public function preview(Submission $submission)
    {
        $class = $submission->assignment->classroom;
        $isTeacher = $class->teacher_id === auth()->id();
        $isOwner   = $submission->student_id === auth()->id();
        abort_unless($isTeacher || $isOwner, 403);

        $path = $submission->drive_file_id;
        abort_if(!$path, 404);

        $stream = Storage::disk('google')->readStream($path);
        abort_if(!$stream, 404);

        // tentukan content-type dari ekstensi
        $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        $mime = match ($ext) {
            'pdf'           => 'application/pdf',
            'jpg', 'jpeg'   => 'image/jpeg',
            'png'           => 'image/png',
            'doc'           => 'application/msword',
            'docx'          => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            default         => 'application/octet-stream',
        };

        return response()->stream(function () use ($stream) {
            fpassthru($stream);
        }, 200, [
            'Content-Type'        => $mime,
            'Content-Disposition' => 'inline; filename="' . basename($path) . '"',
            'Cache-Control'       => 'private, max-age=0, no-cache',
            'Pragma'              => 'no-cache',
        ]);
    }

    public function download(Submission $submission)
    {
        $class = $submission->assignment->classroom;
        $isTeacher = $class->teacher_id === auth()->id();
        $isOwner   = $submission->student_id === auth()->id();
        abort_unless($isTeacher || $isOwner, 403);

        $path = $submission->drive_file_id;
        abort_if(!$path, 404);

        $stream = Storage::disk('google')->readStream($path);
        abort_if(!$stream, 404);

        return response()->streamDownload(function () use ($stream) {
            fpassthru($stream);
        }, basename($path));
    }

    private function authorizeOwner(Classroom $class)
    {
        abort_if(auth()->id() !== $class->teacher_id, 403);
    }

    private function authorizeStudentInClass(Classroom $class)
    {
        $isMember = $class->members()->where('users.id', auth()->id())->exists();
        abort_unless($isMember, 403);
    }
}

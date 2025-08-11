<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\Classroom;
use App\Models\Submission;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->role === 'teacher') {
            // Statistik guru
            $classesCount = Classroom::where('teacher_id', $user->id)->count();

            $assignmentsCount = Assignment::whereHas('classroom', function ($q) use ($user) {
                $q->where('teacher_id', $user->id);
            })->count();

            $toGradeCount = Submission::whereHas('assignment.classroom', function ($q) use ($user) {
                $q->where('teacher_id', $user->id);
            })->where('status', 'turned_in')->count();

            $upcomingAssignments = Assignment::with('classroom')
                ->whereHas('classroom', fn($q) => $q->where('teacher_id', $user->id))
                ->where('status', 'published')
                ->whereNotNull('due_at')
                ->orderBy('due_at')
                ->take(5)
                ->get();

            $recentSubmissions = Submission::with(['assignment.classroom','student'])
                ->whereHas('assignment.classroom', fn($q) => $q->where('teacher_id', $user->id))
                ->latest()
                ->take(5)
                ->get();

            return view('admin.pages.dashboard.overview', [
                'title' => 'Ikhtisar Dasbor',
                'role' => 'teacher',
                'cards' => [
                    'classes' => $classesCount,
                    'assignments' => $assignmentsCount,
                    'to_grade' => $toGradeCount,
                ],
                'upcomingAssignments' => $upcomingAssignments,
                'recentSubmissions' => $recentSubmissions,
            ]);
        }

        // Statistik siswa
        $enrolledClassesCount = $user->classes()->count();

        $openAssignmentsCount = Assignment::whereIn('class_id', $user->classes()->pluck('class_id'))
            ->where('status', 'published')
            ->where(function ($q) use ($user) {
                // Belum kumpul atau status assigned
                $q->whereDoesntHave('submissions', fn($s) => $s->where('student_id', $user->id))
                  ->orWhereHas('submissions', fn($s) => $s->where('student_id', $user->id)->where('status', 'assigned'));
            })
            ->count();

        $dueSoon = Assignment::with('classroom')
            ->whereIn('class_id', $user->classes()->pluck('class_id'))
            ->where('status', 'published')
            ->whereNotNull('due_at')
            ->orderBy('due_at')
            ->take(5)
            ->get();

        $myRecent = Submission::with(['assignment.classroom'])
            ->where('student_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        return view('admin.pages.dashboard.overview', [
            'title' => 'Ikhtisar Dasbor',
            'role' => 'student',
            'cards' => [
                'classes' => $enrolledClassesCount,
                'open_assignments' => $openAssignmentsCount,
            ],
            'dueSoon' => $dueSoon,
            'myRecent' => $myRecent,
        ]);
    }
}

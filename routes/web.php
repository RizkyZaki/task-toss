<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ClassroomController;
use App\Http\Controllers\ClassEnrollmentController;
use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SubmissionController;
use App\Http\Controllers\TeacherController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Auth
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {
    Route::get('login', [AuthController::class, 'login'])->name('login');
    Route::post('login', [AuthController::class, 'authenticated']);

    Route::get('register', [AuthController::class, 'register'])->name('register');
    Route::post('register', [AuthController::class, 'registerStore'])->name('register.post');
});

// Rekomendasi: pakai POST untuk logout
Route::get('logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| App (Dashboard + Fitur)
|--------------------------------------------------------------------------
*/
Route::prefix('dashboard')->middleware('auth')->group(function () {
    Route::get('overview', [DashboardController::class, 'index'])->name('dashboard');

    /*
    |----------------------------------------------------------------------
    | Classes (Classroom)
    |----------------------------------------------------------------------
    | - Semua user login boleh lihat daftar & detail kelas
    | - Hanya teacher yang boleh buat/edit/hapus kelas
    */
    Route::get('classes', [ClassroomController::class, 'index'])->name('classes.index');
    Route::get('classes/create', [ClassroomController::class, 'create'])
        ->name('classes.create')->middleware('role:teacher'); // penting: taruh sebelum {class}
    Route::post('classes', [ClassroomController::class, 'store'])
        ->name('classes.store')->middleware('role:teacher');

    Route::get('classes/{class}', [ClassroomController::class, 'show'])->name('classes.show');

    Route::get('classes/{class}/edit', [ClassroomController::class, 'edit'])
        ->name('classes.edit')->middleware('role:teacher');
    Route::put('classes/{class}', [ClassroomController::class, 'update'])
        ->name('classes.update')->middleware('role:teacher');
    Route::delete('classes/{class}', [ClassroomController::class, 'destroy'])
        ->name('classes.destroy')->middleware('role:teacher');

    // ===== Enrollment (opsional, student) =====
    Route::post('classes/join', [ClassEnrollmentController::class, 'joinByCode'])
        ->name('classes.join')->middleware('role:student');
    Route::delete('classes/{class}/leave', [ClassEnrollmentController::class, 'leave'])
        ->name('classes.leave')->middleware('role:student');

    // (opsional) lihat member kelas (guru/siswa yang tergabung)
    Route::get('classes/{class}/members', [ClassEnrollmentController::class, 'index'])
        ->name('classes.members');
    /*
    |----------------------------------------------------------------------
    | Assignments (nested)
    |----------------------------------------------------------------------
    | - Teacher: CRUD tugas + publish/close
    | - Student: hanya boleh melihat list & detail
    */
    // List & show tugas (siap untuk semua user login yang ada di kelas)
    Route::get('classes/{class}/assignments', [AssignmentController::class, 'index'])
        ->name('classes.assignments.index');

    // Form buat tugas (TEACHER)
    Route::get('classes/{class}/assignments/create', [AssignmentController::class, 'create'])
        ->name('classes.assignments.create')
        ->middleware('role:teacher');

    // Simpan tugas baru (TEACHER)
    Route::post('classes/{class}/assignments', [AssignmentController::class, 'store'])
        ->name('classes.assignments.store')
        ->middleware('role:teacher');

    // SHALLOW routes (pakai id assignment langsung)

    // Detail tugas (ALL logged-in)
    Route::get('assignments/{assignment}', [AssignmentController::class, 'show'])
        ->name('assignments.show');

    // Form edit tugas (TEACHER)
    Route::get('classes/{class}/assignments/{assignment}/edit', [AssignmentController::class, 'edit'])
        ->name('classes.assignments.edit')
        ->middleware('role:teacher');

    // Update tugas (TEACHER)
    Route::put('assignments/{assignment}', [AssignmentController::class, 'update'])
        ->name('assignments.update')
        ->middleware('role:teacher');

    // Hapus tugas (TEACHER)
    Route::delete('assignments/{assignment}', [AssignmentController::class, 'destroy'])
        ->name('assignments.destroy')
        ->middleware('role:teacher');

    // Aksi status (TEACHER)
    Route::post('assignments/{assignment}/publish', [AssignmentController::class, 'publish'])
        ->name('assignments.publish')
        ->middleware('role:teacher');

    Route::post('assignments/{assignment}/close', [AssignmentController::class, 'close'])
        ->name('assignments.close')
        ->middleware('role:teacher');

    /*
    |----------------------------------------------------------------------
    | Submissions (nested)
    |----------------------------------------------------------------------
    | - Student: turn-in / update kiriman sendiri
    | - Teacher: lihat semua submission & nilai
    */
    // Index submissions untuk 1 assignment (teacher only)
    Route::get('assignments/{assignment}/submissions', [SubmissionController::class, 'index'])
        ->name('assignments.submissions.index')
        ->middleware(['auth', 'role:teacher']);

    // Student turn-in (buat submission baru) & update/hapus kiriman sendiri
    Route::post('assignments/{assignment}/submissions', [SubmissionController::class, 'store'])
        ->name('assignments.submissions.store')
        ->middleware(['auth', 'role:student']);

    Route::get('submissions/{submission}', [SubmissionController::class, 'show'])
        ->name('submissions.show')
        ->middleware(['auth']); // validasi kepemilikan/akses di controller/policy

    Route::put('submissions/{submission}', [SubmissionController::class, 'update'])
        ->name('submissions.update')
        ->middleware(['auth', 'role:student']);

    Route::delete('submissions/{submission}', [SubmissionController::class, 'destroy'])
        ->name('submissions.destroy')
        ->middleware(['auth', 'role:student']);

    // Shortcut turn-in (opsional) — student
    Route::post('assignments/{assignment}/turn-in', [SubmissionController::class, 'turnIn'])
        ->name('submissions.turnin')
        ->middleware(['auth', 'role:student']);

    // Grade submission — teacher
    Route::post('submissions/{submission}/grade', [SubmissionController::class, 'grade'])
        ->name('submissions.grade')
        ->middleware(['auth', 'role:teacher']);
    Route::get('submissions/{submission}/preview', [SubmissionController::class, 'preview'])
        ->name('submissions.preview');
    Route::get('submissions/{submission}/download', [SubmissionController::class, 'download'])
        ->name('submissions.download');
    Route::prefix('users')->middleware(['auth', 'role:teacher'])->group(function () {
        // Manajemen Siswa
        Route::get('students',        [StudentController::class, 'index'])->name('students.index');
        Route::get('students/{user}', [StudentController::class, 'show'])->name('students.show');
        Route::delete('students/{user}', [StudentController::class, 'destroy'])->name('students.destroy');

        // Manajemen Guru
        Route::get('teachers',        [TeacherController::class, 'index'])->name('teachers.index');
        Route::get('teachers/create', [TeacherController::class, 'create'])->name('teachers.create');
        Route::get('teachers/{user}', [TeacherController::class, 'show'])->name('teachers.show');
        Route::post('teachers',       [TeacherController::class, 'store'])->name('teachers.store');

        Route::delete('teachers/{user}', [TeacherController::class, 'destroy'])->name('teachers.destroy');
    });
});

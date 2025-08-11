@extends('admin.layout.main')
@section('content-admin')
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <div class="nk-block-head nk-block-head-sm">
                    <div class="nk-block-between">
                        <div class="nk-block-head-content">
                            <h3 class="nk-block-title page-title">{{ $assignment->title }}</h3>
                            <div class="nk-block-des text-soft">
                                <p>Kelas: {{ $class->name }} · Due:
                                    {{ optional($assignment->due_at)->format('d M Y H:i') ?? '-' }}</p>
                            </div>
                        </div>
                        <div class="nk-block-head-content">
                            @if (auth()->id() === $class->teacher_id)
                                <a href="{{ route('classes.assignments.edit', [$class->id, $assignment->id]) }}"
                                    class="btn btn-light">
                                    <em class="icon ni ni-edit"></em><span>Edit</span>
                                </a>
                                <form action="{{ route('assignments.destroy', $assignment->id) }}" method="POST"
                                    onsubmit="return confirm('Hapus tugas ini?');" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-danger"><em
                                            class="icon ni ni-trash"></em><span>Hapus</span></button>
                                </form>
                                @if ($assignment->status !== 'published')
                                    <form action="{{ route('assignments.publish', $assignment->id) }}" method="POST"
                                        class="d-inline">
                                        @csrf <button class="btn btn-primary">Publish</button>
                                    </form>
                                @endif
                                @if ($assignment->status !== 'closed')
                                    <form action="{{ route('assignments.close', $assignment->id) }}" method="POST"
                                        class="d-inline">
                                        @csrf <button class="btn btn-warning">Close</button>
                                    </form>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>

                <div class="nk-block">
                    <div class="card card-bordered">
                        <div class="card-inner">
                            <h6>Instruksi</h6>
                            <p>{{ $assignment->instructions ?: 'Tidak ada instruksi.' }}</p>
                        </div>
                    </div>
                    @if (auth()->id() === $class->teacher_id)
                        <div class="card card-bordered mt-3">
                            <div class="card-inner">
                                <h6>Daftar Submission</h6>
                                @php
                                    $submissions = $assignment->submissions()->with('student')->latest()->get();
                                @endphp

                                @if ($submissions->isEmpty())
                                    <p class="text-soft">Belum ada submission.</p>
                                @else
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Nama Siswa</th>
                                                    <th>Status</th>
                                                    <th>Dikumpulkan</th>
                                                    <th>Nilai</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($submissions as $sub)
                                                    <tr>
                                                        <td>{{ $sub->student->name }}</td>
                                                        <td>
                                                            <span
                                                                class="badge bg-{{ $sub->status === 'graded' ? 'success' : ($sub->status === 'turned_in' ? 'primary' : 'warning') }}">
                                                                {{ ucfirst(str_replace('_', ' ', $sub->status)) }}
                                                            </span>
                                                        </td>
                                                        <td>{{ $sub->submitted_at ? $sub->submitted_at->format('d M Y H:i') : '-' }}
                                                        </td>
                                                        <td>{{ $sub->score !== null ? $sub->score : '-' }}</td>
                                                        <td>
                                                            <a href="{{ route('submissions.show', $sub->id) }}"
                                                                class="btn btn-sm btn-primary">
                                                                <em class="icon ni ni-eye"></em> Lihat
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif

                    @if (auth()->user()->role === 'student')
                        @php
                            $my = $assignment
                                ->submissions()
                                ->where('student_id', auth()->id())
                                ->first();
                        @endphp

                        <div class="card card-bordered mt-3">
                            <div class="card-inner">
                                <h6>{{ $my ? 'Submission Saya' : 'Kumpulkan Tugas' }}</h6>

                                @if ($my)
                                    <p class="text-soft mb-2">
                                        Status: <span
                                            class="badge bg-{{ $my->status === 'graded' ? 'success' : ($my->status === 'turned_in' ? 'primary' : 'warning') }}">
                                            {{ ucfirst(str_replace('_', ' ', $my->status)) }}
                                        </span>
                                        @if ($my->submitted_at)
                                            · Dikumpulkan: {{ $my->submitted_at->format('d M Y H:i') }}
                                        @endif
                                    </p>
                                    <a href="{{ route('submissions.show', $my->id) }}" class="btn btn-primary">
                                        <em class="icon ni ni-eye"></em><span>Lihat / Edit Submission</span>
                                    </a>
                                @else
                                    @php
                                        $isPastDue = $assignment->due_at && $assignment->due_at->isPast();
                                    @endphp
                                    @if ($assignment->status === 'closed' || $isPastDue)
                                        <div class="alert alert-warning">
                                            @if ($assignment->status === 'closed')
                                                Tugas ini sudah ditutup.
                                            @elseif ($isPastDue)
                                                Batas waktu pengumpulan sudah lewat
                                                ({{ $assignment->due_at->format('d M Y H:i') }}).
                                            @endif
                                        </div>
                                    @else
                                        <form action="{{ route('assignments.submissions.store', $assignment->id) }}"
                                            method="POST" enctype="multipart/form-data" class="row g-2">
                                            @csrf
                                            <div class="col-12">
                                                <label class="form-label">Upload Jawaban</label>
                                                <input type="file" name="answer_file"
                                                    class="form-control @error('answer_file') is-invalid @enderror"
                                                    required>
                                                @error('answer_file')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                                <small class="text-soft">PDF/DOC/DOCX/JPG/PNG, max 10MB.</small>
                                            </div>
                                            <div class="col-12">
                                                <button class="btn btn-success">
                                                    <em class="icon ni ni-upload"></em><span>Kumpulkan</span>
                                                </button>
                                            </div>
                                        </form>
                                    @endif

                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

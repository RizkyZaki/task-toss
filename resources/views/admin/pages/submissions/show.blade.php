@extends('admin.layout.main')

@section('content-admin')
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <div class="nk-block-head nk-block-head-sm">
                    <div class="nk-block-between">
                        <div class="nk-block-head-content">
                            <h3 class="nk-block-title page-title">Submission</h3>
                            <div class="nk-block-des text-soft">
                                <p>
                                    Kelas: {{ $submission->assignment->classroom->name }} ·
                                    Tugas: {{ $submission->assignment->title }} ·
                                    Siswa: {{ $submission->student->name }}
                                </p>
                            </div>
                        </div>
                        <div class="nk-block-head-content">
                            <a href="{{ route('assignments.submissions.index', $submission->assignment_id) }}"
                                class="btn btn-light">
                                <em class="icon ni ni-arrow-left"></em><span>Kembali</span>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="nk-block">
                    <div class="row g-gs">
                        <div class="col-xxl-7">
                            <div class="card card-bordered">
                                <div class="card-inner">
                                    <h6>Detail Kiriman</h6>
                                    <dl class="row">
                                        <dt class="col-sm-4">Status</dt>
                                        <dd class="col-sm-8">
                                            <span
                                                class="badge bg-{{ $submission->status === 'graded' ? 'success' : ($submission->status === 'turned_in' ? 'primary' : 'warning') }}">
                                                {{ ucfirst(str_replace('_', ' ', $submission->status)) }}
                                            </span>
                                        </dd>

                                        <dt class="col-sm-4">Dikumpulkan</dt>
                                        <dd class="col-sm-8">
                                            {{ optional($submission->submitted_at)->format('d M Y H:i') ?? '-' }}</dd>

                                        <dt class="col-sm-4">File Jawaban</dt>
                                        <dd class="col-sm-8">
                                            @if ($submission->drive_file_id)
                                                <a href="{{ route('submissions.preview', $submission->id) }}"
                                                    target="_blank" class="btn btn-sm btn-primary">
                                                    <em class="icon ni ni-eye"></em> Lihat
                                                </a>
                                                <a href="{{ route('submissions.download', $submission->id) }}"
                                                    class="btn btn-sm btn-outline-primary">
                                                    <em class="icon ni ni-download"></em> Unduh
                                                </a>
                                            @else
                                                <span class="text-soft">-</span>
                                            @endif
                                        </dd>


                                        <dt class="col-sm-4">Nilai</dt>
                                        <dd class="col-sm-8">{{ $submission->score !== null ? $submission->score : '-' }}
                                        </dd>

                                        <dt class="col-sm-4">Catatan Guru</dt>
                                        <dd class="col-sm-8">{{ $submission->teacher_note ?? '-' }}</dd>
                                    </dl>
                                </div>
                            </div>
                            @if ($submission->drive_file_id && \Illuminate\Support\Str::endsWith(strtolower($submission->drive_file_id), '.pdf'))
                                <div class="card card-bordered mt-3">
                                    <div class="card-inner">
                                        <h6>Pratinjau PDF</h6>
                                        <iframe src="{{ route('submissions.preview', $submission->id) }}" width="100%"
                                            height="600" style="border:0;"></iframe>
                                    </div>
                                </div>
                            @endif

                        </div>

                        <div class="col-xxl-5">
                            {{-- Panel aksi --}}
                            @if ($isTeacher)
                                <div class="card card-bordered">
                                    <div class="card-inner">
                                        <h6>Penilaian</h6>
                                        <form action="{{ route('submissions.grade', $submission->id) }}" method="POST"
                                            class="row g-2">
                                            @csrf
                                            <div class="col-md-4">
                                                <label class="form-label">Nilai</label>
                                                <input type="number" name="score" class="form-control" min="0"
                                                    max="{{ $submission->assignment->max_points }}"
                                                    value="{{ old('score', $submission->score) }}">
                                            </div>
                                            <div class="col-md-8">
                                                <label class="form-label">Catatan</label>
                                                <input type="text" name="teacher_note" class="form-control"
                                                    value="{{ old('teacher_note', $submission->teacher_note) }}">
                                            </div>
                                            <div class="col-12">
                                                <button class="btn btn-success">
                                                    <em class="icon ni ni-check"></em><span>Simpan Nilai</span>
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            @endif

                            @if ($isOwnerStudent && $submission->status !== 'graded')
                                <div class="card card-bordered">
                                    <div class="card-inner">
                                        <h6>Perbarui Kiriman</h6>
                                        <form action="{{ route('submissions.update', $submission->id) }}" method="POST"
                                            class="row g-2" enctype="multipart/form-data">
                                            @csrf @method('PUT')
                                            <div class="col-12">
                                                <label class="form-label">File Jawaban</label>
                                                <input type="file" name="answer_file" class="form-control"
                                                    accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                                                <small class="text-soft">Kosongkan jika tidak ingin mengganti file.</small>
                                            </div>
                                            <div class="col-12">
                                                <button class="btn btn-primary">
                                                    <em class="icon ni ni-save"></em><span>Simpan</span>
                                                </button>
                                                <form action="{{ route('submissions.destroy', $submission->id) }}"
                                                    method="POST" onsubmit="return confirm('Hapus submission ini?')">
                                                    @csrf @method('DELETE')
                                                    <button class="btn btn-danger">
                                                        <em class="icon ni ni-trash"></em><span>Hapus</span>
                                                    </button>
                                                </form>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection

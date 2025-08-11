@extends('admin.layout.main')

@section('content-admin')
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <div class="nk-block-head nk-block-head-sm">
                    <div class="nk-block-between">
                        <div class="nk-block-head-content">
                            <h3 class="nk-block-title page-title">Submission - {{ $assignment->title }}</h3>
                            <div class="nk-block-des text-soft">
                                <p>Kelas: {{ $class->name }}</p>
                            </div>
                        </div>
                        <div class="nk-block-head-content">
                            <a href="{{ route('assignments.show', $assignment->id) }}" class="btn btn-light">
                                <em class="icon ni ni-arrow-left"></em><span>Kembali</span>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="nk-block">
                    <div class="card card-bordered">
                        <div class="card-inner">
                            <div class="nk-tb-list is-separate is-medium mb-3">
                                <div class="nk-tb-item nk-tb-head">
                                    <div class="nk-tb-col"><span>Siswa</span></div>
                                    <div class="nk-tb-col tb-col-md"><span>Status</span></div>
                                    <div class="nk-tb-col tb-col-md"><span>Dikumpulkan</span></div>
                                    <div class="nk-tb-col tb-col-md"><span>Nilai</span></div>
                                    <div class="nk-tb-col tb-col-md"><span>Aksi</span></div>
                                </div>

                                @forelse($submissions as $s)
                                    <div class="nk-tb-item">
                                        <div class="nk-tb-col">{{ $s->student->name ?? '-' }}</div>
                                        <div class="nk-tb-col tb-col-md">
                                            <span
                                                class="badge bg-{{ $s->status === 'graded' ? 'success' : ($s->status === 'turned_in' ? 'primary' : 'warning') }}">
                                                {{ ucfirst(str_replace('_', ' ', $s->status)) }}
                                            </span>
                                        </div>
                                        <div class="nk-tb-col tb-col-md">
                                            {{ optional($s->submitted_at)->format('d M Y H:i') ?? '-' }}</div>
                                        <div class="nk-tb-col tb-col-md">
                                            {{ $s->score !== null ? $s->score : '-' }}
                                        </div>
                                        <div class="nk-tb-col tb-col-md">
                                            <a href="{{ route('submissions.show', $s->id) }}"
                                                class="btn btn-sm btn-primary">
                                                <em class="icon ni ni-eye"></em><span>Lihat</span>
                                            </a>
                                            @if ($s->status !== 'graded')
                                                <form action="{{ route('submissions.grade', $s->id) }}" method="POST"
                                                    class="d-inline-flex align-items-center gap-1">
                                                    @csrf
                                                    <input type="number" name="score"
                                                        class="form-control form-control-sm" style="width:90px"
                                                        min="0" max="{{ $assignment->max_points }}"
                                                        placeholder="Nilai">
                                                    <input type="text" name="teacher_note"
                                                        class="form-control form-control-sm" style="width:180px"
                                                        placeholder="Catatan">
                                                    <button class="btn btn-sm btn-success"><em
                                                            class="icon ni ni-check"></em></button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                @empty
                                    <div class="nk-tb-item">
                                        <div class="nk-tb-col">Belum ada submission.</div>
                                    </div>
                                @endforelse
                            </div>

                            <div class="mt-3">
                                {{ $submissions->links() }}
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection

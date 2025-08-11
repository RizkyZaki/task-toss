@extends('admin.layout.main')

@section('content-admin')
<div class="container-fluid">
    <div class="nk-content-inner">
        <div class="nk-content-body">
            <div class="nk-block-head nk-block-head-sm">
                <div class="nk-block-between">
                    <div class="nk-block-head-content">
                        <h3 class="nk-block-title page-title">Dashboard</h3>
                        <div class="nk-block-des text-soft">
                            <p>{{ $title }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Cards --}}
            <div class="nk-block">
                <div class="row g-gs">

                    @if($role === 'teacher')
                        <div class="col-xxl-3 col-sm-6">
                            <div class="card">
                                <div class="nk-ecwg nk-ecwg1">
                                    <div class="card-inner">
                                        <div class="card-title-group">
                                            <div class="card-title">
                                                <h6 class="title">Kelas Saya</h6>
                                            </div>
                                            <div class="card-tools">
                                                <em class="icon ni ni-book"></em>
                                            </div>
                                        </div>
                                        <div class="data">
                                            <div class="amount">{{ $cards['classes'] ?? 0 }}</div>
                                            <div class="info"><strong>Total</strong></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xxl-3 col-sm-6">
                            <div class="card">
                                <div class="nk-ecwg nk-ecwg1">
                                    <div class="card-inner">
                                        <div class="card-title-group">
                                            <div class="card-title">
                                                <h6 class="title">Tugas Dibuat</h6>
                                            </div>
                                            <div class="card-tools">
                                                <em class="icon ni ni-task"></em>
                                            </div>
                                        </div>
                                        <div class="data">
                                            <div class="amount">{{ $cards['assignments'] ?? 0 }}</div>
                                            <div class="info"><strong>Total</strong></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xxl-3 col-sm-6">
                            <div class="card">
                                <div class="nk-ecwg nk-ecwg1">
                                    <div class="card-inner">
                                        <div class="card-title-group">
                                            <div class="card-title">
                                                <h6 class="title">Menunggu Dinilai</h6>
                                            </div>
                                            <div class="card-tools">
                                                <em class="icon ni ni-clipboad-check"></em>
                                            </div>
                                        </div>
                                        <div class="data">
                                            <div class="amount">{{ $cards['to_grade'] ?? 0 }}</div>
                                            <div class="info"><strong>Submission</strong></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="col-xxl-3 col-sm-6">
                            <div class="card">
                                <div class="nk-ecwg nk-ecwg1">
                                    <div class="card-inner">
                                        <div class="card-title-group">
                                            <div class="card-title">
                                                <h6 class="title">Kelas Diikuti</h6>
                                            </div>
                                            <div class="card-tools">
                                                <em class="icon ni ni-users"></em>
                                            </div>
                                        </div>
                                        <div class="data">
                                            <div class="amount">{{ $cards['classes'] ?? 0 }}</div>
                                            <div class="info"><strong>Total</strong></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xxl-3 col-sm-6">
                            <div class="card">
                                <div class="nk-ecwg nk-ecwg1">
                                    <div class="card-inner">
                                        <div class="card-title-group">
                                            <div class="card-title">
                                                <h6 class="title">Tugas Terbuka</h6>
                                            </div>
                                            <div class="card-tools">
                                                <em class="icon ni ni-task"></em>
                                            </div>
                                        </div>
                                        <div class="data">
                                            <div class="amount">{{ $cards['open_assignments'] ?? 0 }}</div>
                                            <div class="info"><strong>Belum Dikumpulkan</strong></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                </div>
            </div>

            {{-- Lists --}}
            <div class="nk-block">
                <div class="row g-gs">
                    @if($role === 'teacher')
                        {{-- Tugas akan jatuh tempo --}}
                        <div class="col-xxl-6">
                            <div class="card card-bordered card-full">
                                <div class="card-inner">
                                    <div class="card-title-group">
                                        <div class="card-title"><h6 class="title">Tugas Akan Jatuh Tempo</h6></div>
                                    </div>
                                </div>
                                <div class="nk-tb-list is-separate is-medium mb-3">
                                    <div class="nk-tb-item nk-tb-head">
                                        <div class="nk-tb-col"><span>Judul</span></div>
                                        <div class="nk-tb-col tb-col-md"><span>Kelas</span></div>
                                        <div class="nk-tb-col tb-col-md"><span>Jatuh Tempo</span></div>
                                    </div>
                                    @forelse($upcomingAssignments as $a)
                                        <div class="nk-tb-item">
                                            <div class="nk-tb-col">
                                                <a href="{{ route('assignments.show', $a->id) }}">{{ $a->title }}</a>
                                            </div>
                                            <div class="nk-tb-col tb-col-md">{{ $a->classroom->name ?? '-' }}</div>
                                            <div class="nk-tb-col tb-col-md">{{ optional($a->due_at)->format('d M Y H:i') ?? '-' }}</div>
                                        </div>
                                    @empty
                                        <div class="nk-tb-item"><div class="nk-tb-col">Tidak ada data.</div></div>
                                    @endforelse
                                </div>
                            </div>
                        </div>

                        {{-- Submission terbaru --}}
                        <div class="col-xxl-6">
                            <div class="card card-bordered card-full">
                                <div class="card-inner">
                                    <div class="card-title-group">
                                        <div class="card-title"><h6 class="title">Submission Terbaru</h6></div>
                                    </div>
                                </div>
                                <div class="nk-tb-list is-separate is-medium mb-3">
                                    <div class="nk-tb-item nk-tb-head">
                                        <div class="nk-tb-col"><span>Siswa</span></div>
                                        <div class="nk-tb-col tb-col-md"><span>Tugas</span></div>
                                        <div class="nk-tb-col tb-col-md"><span>Kelas</span></div>
                                        <div class="nk-tb-col tb-col-md"><span>Waktu</span></div>
                                    </div>
                                    @forelse($recentSubmissions as $s)
                                        <div class="nk-tb-item">
                                            <div class="nk-tb-col">{{ $s->student->name ?? '-' }}</div>
                                            <div class="nk-tb-col tb-col-md">
                                                <a href="{{ route('submissions.show', $s->id) }}">
                                                    {{ $s->assignment->title ?? '-' }}
                                                </a>
                                            </div>
                                            <div class="nk-tb-col tb-col-md">{{ $s->assignment->classroom->name ?? '-' }}</div>
                                            <div class="nk-tb-col tb-col-md">{{ $s->created_at->format('d M Y H:i') }}</div>
                                        </div>
                                    @empty
                                        <div class="nk-tb-item"><div class="nk-tb-col">Belum ada submission.</div></div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    @else
                        {{-- Tugas segera due --}}
                        <div class="col-xxl-6">
                            <div class="card card-bordered card-full">
                                <div class="card-inner">
                                    <div class="card-title-group">
                                        <div class="card-title"><h6 class="title">Tugas Akan Jatuh Tempo</h6></div>
                                    </div>
                                </div>
                                <div class="nk-tb-list is-separate is-medium mb-3">
                                    <div class="nk-tb-item nk-tb-head">
                                        <div class="nk-tb-col"><span>Judul</span></div>
                                        <div class="nk-tb-col tb-col-md"><span>Kelas</span></div>
                                        <div class="nk-tb-col tb-col-md"><span>Jatuh Tempo</span></div>
                                    </div>
                                    @forelse($dueSoon as $a)
                                        <div class="nk-tb-item">
                                            <div class="nk-tb-col">
                                                <a href="{{ route('assignments.show', $a->id) }}">{{ $a->title }}</a>
                                            </div>
                                            <div class="nk-tb-col tb-col-md">{{ $a->classroom->name ?? '-' }}</div>
                                            <div class="nk-tb-col tb-col-md">{{ optional($a->due_at)->format('d M Y H:i') ?? '-' }}</div>
                                        </div>
                                    @empty
                                        <div class="nk-tb-item"><div class="nk-tb-col">Tidak ada tugas mendekati due.</div></div>
                                    @endforelse
                                </div>
                            </div>
                        </div>

                        {{-- Aktivitas saya --}}
                        <div class="col-xxl-6">
                            <div class="card card-bordered card-full">
                                <div class="card-inner">
                                    <div class="card-title-group">
                                        <div class="card-title"><h6 class="title">Aktivitas Terakhir Saya</h6></div>
                                    </div>
                                </div>
                                <div class="nk-tb-list is-separate is-medium mb-3">
                                    <div class="nk-tb-item nk-tb-head">
                                        <div class="nk-tb-col"><span>Tugas</span></div>
                                        <div class="nk-tb-col tb-col-md"><span>Status</span></div>
                                        <div class="nk-tb-col tb-col-md"><span>Waktu</span></div>
                                    </div>
                                    @forelse($myRecent as $s)
                                        <div class="nk-tb-item">
                                            <div class="nk-tb-col">
                                                <a href="{{ route('submissions.show', $s->id) }}">
                                                    {{ $s->assignment->title ?? '-' }}
                                                </a>
                                            </div>
                                            <div class="nk-tb-col tb-col-md">
                                                <span class="badge bg-{{ $s->status === 'graded' ? 'success' : ($s->status === 'turned_in' ? 'primary' : 'warning') }}">
                                                    {{ ucfirst(str_replace('_', ' ', $s->status)) }}
                                                </span>
                                            </div>
                                            <div class="nk-tb-col tb-col-md">{{ $s->created_at->format('d M Y H:i') }}</div>
                                        </div>
                                    @empty
                                        <div class="nk-tb-item"><div class="nk-tb-col">Belum ada aktivitas.</div></div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

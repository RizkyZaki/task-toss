@extends('admin.layout.main')

@section('content-admin')
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <div class="nk-block-head nk-block-head-sm">
                    <div class="nk-block-between">
                        <div class="nk-block-head-content">
                            <h3 class="nk-block-title page-title">{{ $class->name }}</h3>
                            <div class="nk-block-des text-soft">
                                <p>Kode Kelas: <code>{{ $class->code }}</code></p>
                            </div>
                        </div>
                        <div class="nk-block-head-content">
                            @if (auth()->user()->id === $class->teacher_id)
                                <a href="{{ route('classes.assignments.create', $class->id) }}" class="btn btn-primary">
                                    <em class="icon ni ni-task"></em><span>Buat Tugas</span>
                                </a>
                            @endif
                            @if (auth()->id() === $class->teacher_id)
                                <div class="btn-group">
                                    <a href="{{ route('classes.edit', $class->id) }}" class="btn btn-light">
                                        <em class="icon ni ni-edit"></em><span>Edit Kelas</span>
                                    </a>
                                    <form action="{{ route('classes.destroy', $class->id) }}" method="POST"
                                        onsubmit="return confirm('Hapus kelas ini beserta tugas & submission?');"
                                        class="d-inline">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-danger"><em
                                                class="icon ni ni-trash"></em><span>Hapus</span></button>
                                    </form>
                                </div>
                            @endif

                        </div>
                    </div>
                </div>

                <div class="nk-block">
                    <div class="card card-bordered">
                        <div class="card-inner">
                            <div class="card-title-group">
                                <div class="card-title">
                                    <h6>Daftar Tugas</h6>
                                </div>
                            </div>

                            <div class="nk-tb-list is-separate is-medium mb-3">
                                <div class="nk-tb-item nk-tb-head">
                                    <div class="nk-tb-col"><span>Judul</span></div>
                                    <div class="nk-tb-col tb-col-md"><span>Status</span></div>
                                    <div class="nk-tb-col tb-col-md"><span>Jatuh Tempo</span></div>
                                    <div class="nk-tb-col tb-col-md"><span>Aksi</span></div>
                                </div>
                                @forelse($assignments as $a)
                                    <div class="nk-tb-item">
                                        <div class="nk-tb-col">
                                            <a href="{{ route('assignments.show', $a->id) }}">{{ $a->title }}</a>
                                        </div>
                                        <div class="nk-tb-col tb-col-md">
                                            <span
                                                class="badge bg-{{ $a->status === 'published' ? 'primary' : ($a->status === 'closed' ? 'danger' : 'secondary') }}">
                                                {{ ucfirst($a->status) }}
                                            </span>
                                        </div>
                                        <div class="nk-tb-col tb-col-md">
                                            {{ optional($a->due_at)->format('d M Y H:i') ?? '-' }}</div>
                                        <div class="nk-tb-col tb-col-md">
                                            @if (auth()->user()->id === $class->teacher_id)
                                                <a href="{{ route('classes.assignments.edit', [$class->id, $a->id]) }}"
                                                    class="btn btn-sm btn-light">
                                                    <em class="icon ni ni-edit"></em><span>Edit</span>
                                                </a>
                                            @endif
                                            <a href="{{ route('assignments.show', $a->id) }}"
                                                class="btn btn-sm btn-primary">
                                                <em class="icon ni ni-eye"></em><span>Lihat</span>
                                            </a>
                                        </div>
                                    </div>
                                @empty
                                    <div class="nk-tb-item">
                                        <div class="nk-tb-col">Belum ada tugas.</div>
                                    </div>
                                @endforelse
                            </div>

                            <a href="{{ route('classes.members', $class->id) }}" class="link">
                                <em class="icon ni ni-users"></em> Lihat anggota kelas
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection

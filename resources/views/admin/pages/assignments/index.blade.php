@extends('admin.layout.main')

@section('content-admin')
<div class="container-fluid">
    <div class="nk-content-inner">
        <div class="nk-content-body">
            {{-- Header --}}
            <div class="nk-block-head nk-block-head-sm">
                <div class="nk-block-between">
                    <div class="nk-block-head-content">
                        <h3 class="nk-block-title page-title">
                            Tugas - {{ $class->name }}
                        </h3>
                        <div class="nk-block-des text-soft">
                            <p>Daftar tugas untuk kelas ini.</p>
                        </div>
                    </div>
                    <div class="nk-block-head-content">
                        @if(auth()->id() === $class->teacher_id)
                            <a href="{{ route('classes.assignments.create', $class->id) }}" class="btn btn-primary">
                                <em class="icon ni ni-plus"></em>
                                <span>Buat Tugas</span>
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Table --}}
            <div class="nk-block">
                <div class="card card-bordered">
                    <div class="card-inner">
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
                                        <a href="{{ route('assignments.show', $a->id) }}">
                                            {{ $a->title }}
                                        </a>
                                    </div>
                                    <div class="nk-tb-col tb-col-md">
                                        <span class="badge bg-{{ $a->status === 'published' ? 'primary' : ($a->status === 'closed' ? 'danger' : 'secondary') }}">
                                            {{ ucfirst($a->status) }}
                                        </span>
                                    </div>
                                    <div class="nk-tb-col tb-col-md">
                                        {{ optional($a->due_at)->format('d M Y H:i') ?? '-' }}
                                    </div>
                                    <div class="nk-tb-col tb-col-md">
                                        {{-- Aksi Teacher --}}
                                        @if(auth()->id() === $class->teacher_id)
                                            <a href="{{ route('classes.assignments.edit', [$class->id, $a->id]) }}" class="btn btn-sm btn-light">
                                                <em class="icon ni ni-edit"></em>
                                            </a>
                                            <form action="{{ route('assignments.destroy', $a->id) }}" method="POST" class="d-inline"
                                                  onsubmit="return confirm('Hapus tugas ini?')">
                                                @csrf @method('DELETE')
                                                <button class="btn btn-sm btn-danger">
                                                    <em class="icon ni ni-trash"></em>
                                                </button>
                                            </form>
                                            @if($a->status !== 'published')
                                                <form action="{{ route('assignments.publish', $a->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button class="btn btn-sm btn-primary">Publish</button>
                                                </form>
                                            @endif
                                            @if($a->status !== 'closed')
                                                <form action="{{ route('assignments.close', $a->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button class="btn btn-sm btn-warning">Close</button>
                                                </form>
                                            @endif
                                        @else
                                            {{-- Aksi Student --}}
                                            <a href="{{ route('assignments.show', $a->id) }}" class="btn btn-sm btn-primary">
                                                <em class="icon ni ni-eye"></em><span>Lihat</span>
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <div class="nk-tb-item">
                                    <div class="nk-tb-col">Belum ada tugas.</div>
                                </div>
                            @endforelse
                        </div>

                        {{-- Pagination --}}
                        <div class="mt-3">
                            {{ $assignments->links() }}
                        </div>
                    </div>
                </div>
            </div>

            {{-- Back link --}}
            <div class="mt-2">
                <a href="{{ route('classes.show', $class->id) }}" class="btn btn-light">
                    <em class="icon ni ni-arrow-left"></em> Kembali ke Kelas
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

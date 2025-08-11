@extends('admin.layout.main')

@section('content-admin')
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <div class="nk-block-head nk-block-head-sm">
                    <div class="nk-block-between">
                        <div class="nk-block-head-content">
                            <h3 class="nk-block-title page-title">Anggota Kelas</h3>
                            <div class="nk-block-des text-soft">
                                <p>{{ $class->name }} · Kode: <code>{{ $class->code }}</code></p>
                            </div>
                        </div>
                        <div class="nk-block-head-content">
                            <a href="{{ route('classes.show', $class->id) }}" class="btn btn-light">
                                <em class="icon ni ni-arrow-left"></em><span>Kembali</span>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="nk-block">
                    <div class="card card-bordered">
                        <div class="card-inner">
                            <div class="nk-tb-list is-separate is-medium">
                                <div class="nk-tb-item nk-tb-head">
                                    <div class="nk-tb-col"><span>Nama</span></div>
                                    <div class="nk-tb-col tb-col-md"><span>Email</span></div>
                                    <div class="nk-tb-col tb-col-md"><span>Role</span></div>
                                </div>

                                @forelse($members as $m)
                                    <div class="nk-tb-item">
                                        <div class="nk-tb-col">
                                            <span>{{ $m->name }}</span>
                                        </div>
                                        <div class="nk-tb-col tb-col-md">
                                            <span>{{ $m->email }}</span>
                                        </div>
                                        <div class="nk-tb-col tb-col-md">
                                            <span class="badge bg-{{ $m->role === 'student' ? 'secondary' : 'primary' }}">
                                                {{ ucfirst($m->role) }}
                                            </span>
                                        </div>
                                    </div>
                                @empty
                                    <div class="nk-tb-item">
                                        <div class="nk-tb-col">Belum ada anggota di kelas ini.</div>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    @if (auth()->id() === $class->teacher_id)
                        <div class="alert alert-info mt-3">
                            Bagikan kode kelas <code>{{ $class->code }}</code> kepada siswa untuk bergabung.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

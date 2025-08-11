@extends('admin.layout.main')
@section('content-admin')
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <div class="nk-block-head nk-block-head-sm">
                    <div class="nk-block-between">
                        <div class="nk-block-head-content">
                            <h3 class="nk-block-title page-title">Manajemen Guru</h3>
                            <div class="nk-block-des text-soft">
                                <p>Daftar semua guru.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="nk-block">
                    <div class="card card-bordered">
                        <div class="card-inner">
                            <div class="nk-tb-list is-separate is-medium mb-3">
                                <div class="nk-tb-item nk-tb-head">
                                    <div class="nk-tb-col"><span>Nama</span></div>
                                    <div class="nk-tb-col tb-col-md"><span>Email</span></div>
                                    <div class="nk-tb-col tb-col-md"><span>Aksi</span></div>
                                </div>

                                @forelse($teachers as $u)
                                    <div class="nk-tb-item">
                                        <div class="nk-tb-col">
                                            <a href="{{ route('teachers.show', $u->id) }}">{{ $u->name }}</a>
                                        </div>
                                        <div class="nk-tb-col tb-col-md">{{ $u->email }}</div>
                                        <div class="nk-tb-col tb-col-md">
                                            <a href="{{ route('teachers.show', $u->id) }}" class="btn btn-sm btn-primary">
                                                <em class="icon ni ni-eye"></em><span>Lihat</span>
                                            </a>
                                            @if ($u->id !== auth()->id())
                                                <form action="{{ route('teachers.destroy', $u->id) }}" method="POST"
                                                    class="d-inline" onsubmit="return confirm('Hapus guru ini?');">
                                                    @csrf @method('DELETE')
                                                    <button class="btn btn-sm btn-danger"><em
                                                            class="icon ni ni-trash"></em></button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                @empty
                                    <div class="nk-tb-item">
                                        <div class="nk-tb-col">Belum ada guru.</div>
                                    </div>
                                @endforelse
                            </div>

                            <div class="mt-3">{{ $teachers->links() }}</div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection

@extends('admin.layout.main')
@section('content-admin')
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <div class="nk-block-head nk-block-head-sm">
                    <div class="nk-block-between">
                        <div class="nk-block-head-content">
                            <h3 class="nk-block-title page-title">Detail Guru</h3>
                            <div class="nk-block-des text-soft">
                                <p>{{ $user->name }}</p>
                            </div>
                        </div>
                        <div class="nk-block-head-content">
                            <a href="{{ route('teachers.index') }}" class="btn btn-light">
                                <em class="icon ni ni-arrow-left"></em><span>Kembali</span>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="nk-block">
                    <div class="card card-bordered">
                        <div class="card-inner">
                            <dl class="row">
                                <dt class="col-sm-3">Nama</dt>
                                <dd class="col-sm-9">{{ $user->name }}</dd>
                                <dt class="col-sm-3">Email</dt>
                                <dd class="col-sm-9">{{ $user->email }}</dd>
                                <dt class="col-sm-3">Role</dt>
                                <dd class="col-sm-9"><span class="badge bg-secondary">{{ $user->role }}</span></dd>
                                <dt class="col-sm-3">Bergabung</dt>
                                <dd class="col-sm-9">{{ $user->created_at->format('d M Y H:i') }}</dd>
                            </dl>

                            @if ($user->id !== auth()->id())
                                <form action="{{ route('teachers.destroy', $user->id) }}" method="POST"
                                    onsubmit="return confirm('Hapus guru ini?');">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-danger">
                                        <em class="icon ni ni-trash"></em><span>Hapus</span>
                                    </button>
                                </form>
                            @else
                                <div class="alert alert-info mt-2">Anda tidak dapat menghapus akun Anda sendiri.</div>
                            @endif
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection

@extends('admin.layout.main')

@section('content-admin')
<div class="container-fluid">
    <div class="nk-content-inner">
        <div class="nk-content-body">
            <div class="nk-block-head nk-block-head-sm">
                <div class="nk-block-between">
                    <div class="nk-block-head-content">
                        <h3 class="nk-block-title page-title">Kelas</h3>
                        <div class="nk-block-des text-soft">
                            <p>Kelola kelas & bergabung menggunakan kode.</p>
                        </div>
                    </div>
                    <div class="nk-block-head-content">
                        @if(auth()->user()->role === 'teacher')
                            <a href="{{ route('classes.create') }}" class="btn btn-primary">
                                <em class="icon ni ni-plus"></em><span>Buat Kelas</span>
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            <div class="nk-block">
                <div class="row g-gs">
                    @if(auth()->user()->role === 'teacher')
                    <div class="col-xxl-6">
                        <div class="card card-bordered">
                            <div class="card-inner">
                                <div class="card-title-group">
                                    <div class="card-title"><h6>Kelas yang Saya Ajar</h6></div>
                                </div>
                                <ul class="nk-tb-list is-separate is-medium">
                                    @forelse($teaching as $c)
                                        <li class="nk-tb-item">
                                            <div class="nk-tb-col">
                                                <a href="{{ route('classes.show', $c->id) }}">{{ $c->name }}</a>
                                                <div class="sub-text">Kode: <code>{{ $c->code }}</code></div>
                                            </div>
                                        </li>
                                    @empty
                                        <li class="nk-tb-item"><div class="nk-tb-col">Belum ada kelas.</div></li>
                                    @endforelse
                                </ul>
                            </div>
                        </div>
                    </div>
                    @endif
                    @if(auth()->user()->role === 'student')
                    <div class="col-xxl-6">
                        <div class="card card-bordered">
                            <div class="card-inner">
                                <div class="card-title-group">
                                    <div class="card-title"><h6>Gabung Kelas</h6></div>
                                </div>
                                <form action="{{ route('classes.join') }}" method="POST" class="row g-2">
                                    @csrf
                                    <div class="col-md-8">
                                        <input type="text" name="code" class="form-control @error('code') is-invalid @enderror"
                                                placeholder="Masukkan kode kelas (6 huruf)">
                                        @error('code') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="col-md-4">
                                        <button class="btn btn-primary w-100" type="submit">
                                            <em class="icon ni ni-users"></em><span>Gabung</span>
                                        </button>
                                    </div>
                                </form>
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

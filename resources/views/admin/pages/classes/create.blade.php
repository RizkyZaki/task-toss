@extends('admin.layout.main')

@section('content-admin')
<div class="container-fluid">
    <div class="nk-content-inner">
        <div class="nk-content-body">
            <div class="nk-block-head nk-block-head-sm">
                <div class="nk-block-between">
                    <div class="nk-block-head-content">
                        <h3 class="nk-block-title page-title">Buat Kelas</h3>
                    </div>
                </div>
            </div>

            <div class="nk-block">
                <div class="card card-bordered">
                    <div class="card-inner">
                        <form action="{{ route('classes.store') }}" method="POST" class="row g-3">
                            @csrf
                            <div class="col-md-8">
                                <label class="form-label">Nama Kelas</label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                       placeholder="Misal: Matematika X-1" value="{{ old('name') }}" required>
                                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-4 d-flex align-items-end">
                                <button class="btn btn-primary" type="submit">
                                    <em class="icon ni ni-plus"></em><span>Buat</span>
                                </button>
                            </div>
                        </form>
                        <div class="text-soft mt-2"><small>Kode kelas akan dibuat otomatis.</small></div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@extends('admin.layout.main')

@section('content-admin')
<div class="container-fluid">
  <div class="nk-content-inner"><div class="nk-content-body">
    <div class="nk-block-head nk-block-head-sm">
      <div class="nk-block-between">
        <div class="nk-block-head-content">
          <h3 class="nk-block-title page-title">Edit Kelas</h3>
          <div class="nk-block-des text-soft"><p>Kode: <code>{{ $class->code }}</code></p></div>
        </div>
        <div class="nk-block-head-content">
          <form action="{{ route('classes.destroy', $class->id) }}" method="POST" onsubmit="return confirm('Hapus kelas ini? Tugas & submission akan ikut terhapus.');">
            @csrf @method('DELETE')
            <button class="btn btn-danger"><em class="icon ni ni-trash"></em><span>Hapus</span></button>
          </form>
        </div>
      </div>
    </div>

    <div class="nk-block">
      <div class="card card-bordered">
        <div class="card-inner">
          <form action="{{ route('classes.update', $class->id) }}" method="POST" class="row g-3">
            @csrf @method('PUT')
            <div class="col-md-8">
              <label class="form-label">Nama Kelas</label>
              <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                     value="{{ old('name', $class->name) }}" required>
              @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4 d-flex align-items-end">
              <div class="form-check">
                <input class="form-check-input" type="checkbox" name="is_active" value="1" id="activeCheck" {{ $class->is_active ? 'checked' : '' }}>
                <label class="form-check-label" for="activeCheck">Aktif</label>
              </div>
            </div>
            <div class="col-12">
              <button class="btn btn-primary"><em class="icon ni ni-save"></em><span>Simpan</span></button>
              <a href="{{ route('classes.show', $class->id) }}" class="btn btn-light">Batal</a>
            </div>
          </form>
        </div>
      </div>
    </div>

  </div></div>
</div>
@endsection

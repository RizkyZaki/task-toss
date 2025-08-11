@extends('admin.layout.main')
@section('content-admin')
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <div class="nk-block-head nk-block-head-sm">
                    <div class="nk-block-between">
                        <div class="nk-block-head-content">
                            <h3 class="nk-block-title page-title">Buat Tugas ({{ $class->name }})</h3>
                        </div>
                    </div>
                </div>

                <div class="nk-block">
                    <div class="card card-bordered">
                        <div class="card-inner">
                            <form action="{{ route('classes.assignments.store', $class->id) }}" method="POST"
                                class="row g-3">
                                @csrf
                                <div class="col-12">
                                    <label class="form-label">Judul</label>
                                    <input type="text" name="title"
                                        class="form-control @error('title') is-invalid @enderror" required>
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Instruksi</label>
                                    <textarea name="instructions" class="form-control" rows="5"></textarea>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Jatuh Tempo</label>
                                    <input type="datetime-local" name="due_at" class="form-control">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Max Poin</label>
                                    <input type="number" name="max_points" class="form-control" value="100"
                                        min="1" max="1000">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Status</label>
                                    <select name="status" class="form-control">
                                        <option value="draft">Draft</option>
                                        <option value="published">Published</option>
                                        <option value="closed">Closed</option>
                                    </select>
                                </div>
                                <div class="col-12">
                                    <button class="btn btn-primary"><em
                                            class="icon ni ni-save"></em><span>Simpan</span></button>
                                    <a href="{{ route('classes.show', $class->id) }}" class="btn btn-light">Batal</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@extends('admin.layout.main')
@section('content-admin')
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <div class="nk-block-head nk-block-head-sm">
                    <div class="nk-block-between">
                        <div class="nk-block-head-content">
                            <h3 class="nk-block-title page-title">Edit Tugas ({{ $class->name }})</h3>
                        </div>
                        <div class="nk-block-head-content">
                            <form action="{{ route('assignments.destroy', $assignment->id) }}" method="POST"
                                onsubmit="return confirm('Hapus tugas ini? Semua submission tetap ada tapi tak lagi terhubung.');">
                                @csrf @method('DELETE')
                                <button class="btn btn-danger"><em class="icon ni ni-trash"></em><span>Hapus</span></button>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="nk-block">
                    <div class="card card-bordered">
                        <div class="card-inner">
                            <form action="{{ route('assignments.update', $assignment->id) }}" method="POST"
                                class="row g-3">
                                @csrf @method('PUT')
                                <div class="col-12">
                                    <label class="form-label">Judul</label>
                                    <input type="text" name="title" class="form-control"
                                        value="{{ old('title', $assignment->title) }}" required>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Instruksi</label>
                                    <textarea name="instructions" class="form-control" rows="5">{{ old('instructions', $assignment->instructions) }}</textarea>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Jatuh Tempo</label>
                                    <input type="datetime-local" name="due_at" class="form-control"
                                        value="{{ optional($assignment->due_at)->format('Y-m-d\TH:i') }}">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Max Poin</label>
                                    <input type="number" name="max_points" class="form-control"
                                        value="{{ $assignment->max_points }}" min="1" max="1000">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Status</label>
                                    <select name="status" class="form-control">
                                        @foreach (['draft', 'published', 'closed'] as $st)
                                            <option value="{{ $st }}" @selected($assignment->status === $st)>
                                                {{ ucfirst($st) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-12">
                                    <button class="btn btn-primary"><em
                                            class="icon ni ni-save"></em><span>Simpan</span></button>
                                    <a href="{{ route('assignments.show', $assignment->id) }}"
                                        class="btn btn-light">Batal</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

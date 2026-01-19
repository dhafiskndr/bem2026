@extends('layouts.app')

@section('title', 'Edit Surat - BEM Management System')
@section('page-title', 'Edit Surat')

@section('content')
<div style="max-width: 800px; margin: 0 auto;">
    <div style="margin-bottom: 1.5rem;">
        <a href="{{ route('surat') }}" style="color: #667eea; text-decoration: none; font-size: 0.9rem;">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <div style="background: white; border-radius: 0.75rem; padding: 2rem; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05); border: 1px solid #e5e7eb;">
        <h2 style="font-size: 1.5rem; font-weight: 600; color: #1f2937; margin-bottom: 1.5rem;">Edit Surat</h2>

        <form action="{{ route('surat.update', $surat) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label class="form-label">Nomor Surat</label>
                <input type="text" name="nomor_surat" value="{{ $surat->nomor_surat }}" class="form-input @error('nomor_surat') border-red-500 @enderror" required>
                @error('nomor_surat')
                    <small style="color: #ef4444;">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Tipe Surat</label>
                <select name="tipe" class="form-input @error('tipe') border-red-500 @enderror" required>
                    <option value="masuk" {{ $surat->tipe === 'masuk' ? 'selected' : '' }}>Surat Masuk</option>
                    <option value="keluar" {{ $surat->tipe === 'keluar' ? 'selected' : '' }}>Surat Keluar</option>
                </select>
                @error('tipe')
                    <small style="color: #ef4444;">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Perihal</label>
                <textarea name="perihal" class="form-input @error('perihal') border-red-500 @enderror" rows="3" required>{{ $surat->perihal }}</textarea>
                @error('perihal')
                    <small style="color: #ef4444;">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Tanggal Surat</label>
                <input type="date" name="tanggal_surat" value="{{ $surat->tanggal_surat->format('Y-m-d') }}" class="form-input @error('tanggal_surat') border-red-500 @enderror" required>
                @error('tanggal_surat')
                    <small style="color: #ef4444;">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Dari/Tujuan</label>
                <input type="text" name="dari_tujuan" value="{{ $surat->dari_tujuan }}" class="form-input">
            </div>

            <div class="form-group">
                <label class="form-label">Keterangan</label>
                <textarea name="keterangan" class="form-input" rows="2">{{ $surat->keterangan }}</textarea>
            </div>

            <div class="form-group">
                <label class="form-label">File/Dokumen</label>
                @if($surat->file_path)
                    <div style="margin-bottom: 1rem;">
                        <p style="margin: 0 0 0.5rem 0; color: #6b7280; font-size: 0.9rem;">File saat ini:</p>
                        <a href="{{ asset('storage/' . $surat->file_path) }}" target="_blank" style="color: #667eea; text-decoration: none;">
                            <i class="fas fa-file"></i> Lihat File
                        </a>
                    </div>
                @endif
                <input type="file" name="file_path" class="form-input @error('file_path') border-red-500 @enderror">
                <small style="color: #6b7280; display: block; margin-top: 0.25rem;">Format: PDF, DOC, DOCX (Max: 5MB)</small>
                @error('file_path')
                    <small style="color: #ef4444;">{{ $message }}</small>
                @enderror
            </div>

            <div style="display: flex; gap: 1rem; justify-content: flex-end; margin-top: 2rem;">
                <a href="{{ route('surat') }}" class="btn btn-secondary">
                    Batal
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Update Surat
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

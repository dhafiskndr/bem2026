@extends('layouts.app')

@section('title', 'Detail Surat - BEM Management System')
@section('page-title', 'Detail Surat')

@section('content')
<div style="max-width: 800px; margin: 0 auto;">
    <div style="margin-bottom: 1.5rem;">
        <a href="{{ route('surat') }}" style="color: #667eea; text-decoration: none; font-size: 0.9rem;">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <div style="background: white; border-radius: 0.75rem; padding: 2rem; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05); border: 1px solid #e5e7eb;">
        <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 2rem; padding-bottom: 1.5rem; border-bottom: 1px solid #e5e7eb;">
            <div>
                <h2 style="font-size: 1.5rem; font-weight: 600; color: #1f2937; margin: 0 0 0.5rem 0;">{{ $surat->nomor_surat }}</h2>
                <span style="background-color: {{ $surat->tipe === 'masuk' ? '#d1fae5' : '#dbeafe' }}; color: {{ $surat->tipe === 'masuk' ? '#065f46' : '#0c2340' }}; padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.85rem; font-weight: 500;">
                    {{ $surat->tipe_label }}
                </span>
            </div>
            <div style="display: flex; gap: 0.5rem;">
                <a href="{{ route('surat.edit', $surat) }}" class="btn btn-primary" style="padding: 0.5rem 1rem;">
                    <i class="fas fa-edit"></i> Edit
                </a>
                <form action="{{ route('surat.destroy', $surat) }}" method="POST" style="display: inline;" onsubmit="return confirm('Yakin ingin menghapus surat ini?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" style="padding: 0.5rem 1rem;">
                        <i class="fas fa-trash"></i> Hapus
                    </button>
                </form>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-bottom: 2rem;">
            <div>
                <div style="margin-bottom: 1.5rem;">
                    <label style="display: block; color: #6b7280; font-size: 0.9rem; margin-bottom: 0.5rem;">Perihal</label>
                    <p style="margin: 0; color: #1f2937; font-weight: 500;">{{ $surat->perihal }}</p>
                </div>
                <div style="margin-bottom: 1.5rem;">
                    <label style="display: block; color: #6b7280; font-size: 0.9rem; margin-bottom: 0.5rem;">Dari/Tujuan</label>
                    <p style="margin: 0; color: #1f2937; font-weight: 500;">{{ $surat->dari_tujuan ?? '-' }}</p>
                </div>
            </div>
            <div>
                <div style="margin-bottom: 1.5rem;">
                    <label style="display: block; color: #6b7280; font-size: 0.9rem; margin-bottom: 0.5rem;">Tanggal Surat</label>
                    <p style="margin: 0; color: #1f2937; font-weight: 500;">{{ $surat->tanggal_format }}</p>
                </div>
                <div style="margin-bottom: 1.5rem;">
                    <label style="display: block; color: #6b7280; font-size: 0.9rem; margin-bottom: 0.5rem;">Dibuat Oleh</label>
                    <p style="margin: 0; color: #1f2937; font-weight: 500;">{{ $surat->created_by }}</p>
                </div>
            </div>
        </div>

        @if($surat->keterangan)
            <div style="margin-bottom: 2rem; padding: 1rem; background-color: #f9fafb; border-radius: 0.5rem; border-left: 4px solid #667eea;">
                <label style="display: block; color: #6b7280; font-size: 0.9rem; margin-bottom: 0.5rem;">Keterangan</label>
                <p style="margin: 0; color: #1f2937;">{{ $surat->keterangan }}</p>
            </div>
        @endif

        @if($surat->file_path)
            <div style="padding: 1rem; background-color: #dbeafe; border-radius: 0.5rem; border: 1px solid #93c5fd;">
                <div style="display: flex; align-items: center; gap: 1rem;">
                    <i class="fas fa-file-pdf" style="font-size: 2rem; color: #0c2340;"></i>
                    <div>
                        <p style="margin: 0 0 0.5rem 0; color: #0c2340; font-weight: 600;">File Terlampir</p>
                        <a href="{{ asset('storage/' . $surat->file_path) }}" target="_blank" class="btn btn-primary" style="padding: 0.5rem 1rem;">
                            <i class="fas fa-download"></i> Download File
                        </a>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

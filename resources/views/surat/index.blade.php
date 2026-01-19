@extends('layouts.app')

@section('title', 'Surat Masuk/Keluar - BEM Management System')
@section('page-title', 'Surat Masuk/Keluar')

@section('content')
<div style="margin-bottom: 1.5rem; display: flex; justify-content: space-between; align-items: center;">
    <div>
        <h2 style="font-size: 1.25rem; font-weight: 600; color: #1f2937; margin-bottom: 0.25rem;">
            Manajemen Surat
        </h2>
        <p style="color: #6b7280; font-size: 0.9rem;">Kelola semua surat masuk dan keluar organisasi</p>
    </div>
    @if(Auth::user()->role === 'admin')
    <button type="button" class="btn btn-primary" onclick="document.getElementById('tambahSuratModal').style.display='flex'">
        <i class="fas fa-plus"></i> Tambah Surat
    </button>
    @endif
</div>

<!-- Filter -->
<div style="background: white; border-radius: 0.75rem; padding: 1.5rem; margin-bottom: 1.5rem; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05); border: 1px solid #e5e7eb;">
    <form method="GET" action="{{ route('surat') }}" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 1rem;">
        <div>
            <label style="display: block; font-weight: 500; color: #374151; font-size: 0.85rem; margin-bottom: 0.5rem;">Tipe Surat</label>
            <select name="tipe" class="form-input" style="font-size: 0.9rem;">
                <option value="">Semua</option>
                <option value="masuk" {{ request('tipe') == 'masuk' ? 'selected' : '' }}>Surat Masuk</option>
                <option value="keluar" {{ request('tipe') == 'keluar' ? 'selected' : '' }}>Surat Keluar</option>
            </select>
        </div>
        <div>
            <label style="display: block; font-weight: 500; color: #374151; font-size: 0.85rem; margin-bottom: 0.5rem;">Bulan</label>
            <select name="bulan" class="form-input" style="font-size: 0.9rem;">
                <option value="">Semua Bulan</option>
                <option value="01" {{ request('bulan') == '01' ? 'selected' : '' }}>Januari</option>
                <option value="02" {{ request('bulan') == '02' ? 'selected' : '' }}>Februari</option>
                <option value="03" {{ request('bulan') == '03' ? 'selected' : '' }}>Maret</option>
                <option value="04" {{ request('bulan') == '04' ? 'selected' : '' }}>April</option>
                <option value="05" {{ request('bulan') == '05' ? 'selected' : '' }}>Mei</option>
                <option value="06" {{ request('bulan') == '06' ? 'selected' : '' }}>Juni</option>
                <option value="07" {{ request('bulan') == '07' ? 'selected' : '' }}>Juli</option>
                <option value="08" {{ request('bulan') == '08' ? 'selected' : '' }}>Agustus</option>
                <option value="09" {{ request('bulan') == '09' ? 'selected' : '' }}>September</option>
                <option value="10" {{ request('bulan') == '10' ? 'selected' : '' }}>Oktober</option>
                <option value="11" {{ request('bulan') == '11' ? 'selected' : '' }}>November</option>
                <option value="12" {{ request('bulan') == '12' ? 'selected' : '' }}>Desember</option>
            </select>
        </div>
        <div>
            <label style="display: block; font-weight: 500; color: #374151; font-size: 0.85rem; margin-bottom: 0.5rem;">Cari</label>
            <div style="display: flex; gap: 0.5rem;">
                <input type="text" name="search" class="form-input" placeholder="Cari nomor atau perihal..." value="{{ request('search') }}" style="font-size: 0.9rem;">
                <button type="submit" class="btn btn-primary" style="padding: 0.75rem 1rem;">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </div>
    </form>
</div>

<!-- Tabel Surat -->
<div style="background: white; border-radius: 0.75rem; overflow: hidden; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05); border: 1px solid #e5e7eb;">
    @if($surats->count() > 0)
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background-color: #f9fafb; border-bottom: 2px solid #e5e7eb;">
                    <th style="padding: 1rem; text-align: left; font-weight: 600; color: #374151; font-size: 0.85rem;">No. Surat</th>
                    <th style="padding: 1rem; text-align: left; font-weight: 600; color: #374151; font-size: 0.85rem;">Perihal</th>
                    <th style="padding: 1rem; text-align: left; font-weight: 600; color: #374151; font-size: 0.85rem;">Tipe</th>
                    <th style="padding: 1rem; text-align: left; font-weight: 600; color: #374151; font-size: 0.85rem;">Tanggal</th>
                    <th style="padding: 1rem; text-align: left; font-weight: 600; color: #374151; font-size: 0.85rem;">Dari/Tujuan</th>
                    <th style="padding: 1rem; text-align: center; font-weight: 600; color: #374151; font-size: 0.85rem;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($surats as $surat)
                <tr style="border-bottom: 1px solid #e5e7eb; {{ $loop->odd ? '' : 'background-color: #f9fafb;' }}">
                    <td style="padding: 1rem; color: #1f2937; font-weight: 500;">{{ $surat->nomor_surat }}</td>
                    <td style="padding: 1rem; color: #1f2937;">{{ Str::limit($surat->perihal, 40) }}</td>
                    <td style="padding: 1rem;">
                        <span style="background-color: {{ $surat->tipe === 'masuk' ? '#d1fae5' : '#dbeafe' }}; color: {{ $surat->tipe === 'masuk' ? '#065f46' : '#0c2340' }}; padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.85rem; font-weight: 500;">
                            {{ $surat->tipe_label }}
                        </span>
                    </td>
                    <td style="padding: 1rem; color: #6b7280;">{{ $surat->tanggal_format }}</td>
                    <td style="padding: 1rem; color: #6b7280;">{{ $surat->dari_tujuan ?? '-' }}</td>
                    <td style="padding: 1rem; text-align: center;">
                        <button type="button" class="btn" style="background: none; color: #667eea; padding: 0.25rem 0.5rem; border: none; cursor: pointer; font-size: 0.9rem;" onclick="viewSurat({{ $surat->id }})">
                            <i class="fas fa-eye"></i>
                        </button>
                        @if(Auth::user()->role === 'admin')
                        <button type="button" class="btn" style="background: none; color: #667eea; padding: 0.25rem 0.5rem; border: none; cursor: pointer; font-size: 0.9rem;" onclick="editSurat({{ $surat->id }})">
                            <i class="fas fa-edit"></i>
                        </button>
                        <form action="{{ route('surat.destroy', $surat) }}" method="POST" style="display: inline;" onsubmit="return confirm('Yakin ingin menghapus surat ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn" style="background: none; color: #ef4444; padding: 0.25rem 0.5rem; border: none; cursor: pointer; font-size: 0.9rem;">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        
        <!-- Pagination -->
        <div style="padding: 1.5rem; display: flex; justify-content: center;">
            {{ $surats->links() }}
        </div>
    @else
        <div style="padding: 3rem; text-align: center; color: #6b7280;">
            <i class="fas fa-inbox" style="font-size: 3rem; margin-bottom: 1rem; color: #d1d5db;"></i>
            <p>Belum ada data surat.</p>
        </div>
    @endif
</div>

@if(Auth::user()->role === 'admin')
<!-- Modal Tambah Surat -->
<div id="tambahSuratModal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0, 0, 0, 0.5); z-index: 1000; align-items: center; justify-content: center;">
    <div style="background: white; border-radius: 1rem; padding: 2rem; max-width: 600px; width: 90%; max-height: 90vh; overflow-y: auto; box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; border-bottom: 1px solid #e5e7eb; padding-bottom: 1rem;">
            <h3 style="font-size: 1.25rem; font-weight: 600; color: #1f2937; margin: 0;">Tambah Surat Baru</h3>
            <button type="button" onclick="document.getElementById('tambahSuratModal').style.display='none'" style="background: none; border: none; cursor: pointer; color: #6b7280; font-size: 1.5rem;">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <form action="{{ route('surat.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="form-group">
                <label class="form-label">Nomor Surat</label>
                <input type="text" name="nomor_surat" class="form-input @error('nomor_surat') border-red-500 @enderror" placeholder="Contoh: 001/SK/2026" required>
                @error('nomor_surat')
                    <small style="color: #ef4444;">{{ $message }}</small>
                @enderror
            </div>
            
            <div class="form-group">
                <label class="form-label">Tipe Surat</label>
                <select name="tipe" class="form-input @error('tipe') border-red-500 @enderror" required>
                    <option value="">-- Pilih --</option>
                    <option value="masuk">Surat Masuk</option>
                    <option value="keluar">Surat Keluar</option>
                </select>
                @error('tipe')
                    <small style="color: #ef4444;">{{ $message }}</small>
                @enderror
            </div>
            
            <div class="form-group">
                <label class="form-label">Perihal</label>
                <textarea name="perihal" class="form-input @error('perihal') border-red-500 @enderror" rows="3" placeholder="Masukkan perihal surat..." required></textarea>
                @error('perihal')
                    <small style="color: #ef4444;">{{ $message }}</small>
                @enderror
            </div>
            
            <div class="form-group">
                <label class="form-label">Tanggal Surat</label>
                <input type="date" name="tanggal_surat" class="form-input @error('tanggal_surat') border-red-500 @enderror" required>
                @error('tanggal_surat')
                    <small style="color: #ef4444;">{{ $message }}</small>
                @enderror
            </div>
            
            <div class="form-group">
                <label class="form-label">Dari/Tujuan</label>
                <input type="text" name="dari_tujuan" class="form-input" placeholder="Contoh: PT Mitra Jaya">
            </div>
            
            <div class="form-group">
                <label class="form-label">Keterangan</label>
                <textarea name="keterangan" class="form-input" rows="2" placeholder="Keterangan tambahan..."></textarea>
            </div>
            
            <div class="form-group">
                <label class="form-label">File/Dokumen</label>
                <input type="file" name="file_path" class="form-input @error('file_path') border-red-500 @enderror">
                <small style="color: #6b7280; display: block; margin-top: 0.25rem;">Format: PDF, DOC, DOCX (Max: 5MB)</small>
                @error('file_path')
                    <small style="color: #ef4444;">{{ $message }}</small>
                @enderror
            </div>
            
            <div style="display: flex; gap: 1rem; justify-content: flex-end;">
                <button type="button" class="btn btn-secondary" onclick="document.getElementById('tambahSuratModal').style.display='none'">
                    Batal
                </button>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Simpan Surat
                </button>
            </div>
        </form>
    </div>
</div>
@endif

<!-- Modal Lihat Surat -->
<div id="lihatSuratModal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0, 0, 0, 0.5); z-index: 1000; align-items: center; justify-content: center;">
    <div style="background: white; border-radius: 1rem; padding: 2rem; max-width: 600px; width: 90%; max-height: 90vh; overflow-y: auto; box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; border-bottom: 1px solid #e5e7eb; padding-bottom: 1rem;">
            <h3 style="font-size: 1.25rem; font-weight: 600; color: #1f2937; margin: 0;">Detail Surat</h3>
            <button type="button" onclick="closeLihatSuratModal()" style="background: none; border: none; cursor: pointer; color: #6b7280; font-size: 1.5rem;">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <div id="lihatSuratContent">
            <div style="text-align: center; padding: 2rem;">
                <i class="fas fa-spinner fa-spin" style="font-size: 2rem; color: #667eea;"></i>
                <p style="color: #6b7280; margin-top: 1rem;">Memuat data...</p>
            </div>
        </div>
    </div>
</div>

<!-- Modal Edit Surat -->
@if(Auth::user()->role === 'admin')
<div id="editSuratModal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0, 0, 0, 0.5); z-index: 1000; align-items: center; justify-content: center;">
    <div style="background: white; border-radius: 1rem; padding: 2rem; max-width: 600px; width: 90%; max-height: 90vh; overflow-y: auto; box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; border-bottom: 1px solid #e5e7eb; padding-bottom: 1rem;">
            <h3 style="font-size: 1.25rem; font-weight: 600; color: #1f2937; margin: 0;">Edit Surat</h3>
            <button type="button" onclick="closeEditSuratModal()" style="background: none; border: none; cursor: pointer; color: #6b7280; font-size: 1.5rem;">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <div id="editSuratContent">
            <div style="text-align: center; padding: 2rem;">
                <i class="fas fa-spinner fa-spin" style="font-size: 2rem; color: #667eea;"></i>
                <p style="color: #6b7280; margin-top: 1rem;">Memuat data...</p>
            </div>
        </div>
    </div>
</div>
@endif

<script>
function viewSurat(suratId) {
    document.getElementById('lihatSuratModal').style.display = 'flex';
    
    fetch(`/surat/${suratId}/data`)
        .then(response => response.json())
        .then(data => {
            let fileHTML = '';
            if (data.file_path) {
                fileHTML = `<div style="margin-top: 1rem; padding-top: 1rem; border-top: 1px solid #e5e7eb;">
                    <strong style="color: #374151;">File:</strong>
                    <a href="${data.file_path}" target="_blank" style="display: inline-block; margin-top: 0.5rem; color: #667eea; text-decoration: none; padding: 0.5rem 1rem; background-color: #f3f4f6; border-radius: 0.5rem;">
                        <i class="fas fa-file-download"></i> Download File
                    </a>
                </div>`;
            }
            
            const content = `
                <div style="margin-bottom: 1rem;">
                    <div style="margin-bottom: 1rem;">
                        <strong style="color: #374151;">Nomor Surat:</strong>
                        <p style="color: #1f2937; margin: 0.25rem 0 0 0; font-size: 0.95rem;">${data.nomor_surat}</p>
                    </div>
                    <div style="margin-bottom: 1rem;">
                        <strong style="color: #374151;">Tipe Surat:</strong>
                        <p style="margin: 0.25rem 0 0 0;">
                            <span style="background-color: ${data.tipe === 'masuk' ? '#d1fae5' : '#dbeafe'}; color: ${data.tipe === 'masuk' ? '#065f46' : '#0c2340'}; padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.85rem; font-weight: 500;">
                                ${data.tipe_label}
                            </span>
                        </p>
                    </div>
                    <div style="margin-bottom: 1rem;">
                        <strong style="color: #374151;">Perihal:</strong>
                        <p style="color: #1f2937; margin: 0.25rem 0 0 0; font-size: 0.95rem;">${data.perihal}</p>
                    </div>
                    <div style="margin-bottom: 1rem;">
                        <strong style="color: #374151;">Tanggal Surat:</strong>
                        <p style="color: #1f2937; margin: 0.25rem 0 0 0; font-size: 0.95rem;">${data.tanggal_format}</p>
                    </div>
                    <div style="margin-bottom: 1rem;">
                        <strong style="color: #374151;">Dari/Tujuan:</strong>
                        <p style="color: #1f2937; margin: 0.25rem 0 0 0; font-size: 0.95rem;">${data.dari_tujuan || '-'}</p>
                    </div>
                    <div style="margin-bottom: 1rem;">
                        <strong style="color: #374151;">Keterangan:</strong>
                        <p style="color: #1f2937; margin: 0.25rem 0 0 0; font-size: 0.95rem;">${data.keterangan || '-'}</p>
                    </div>
                    <div style="color: #6b7280; font-size: 0.85rem; margin-top: 1rem; padding-top: 1rem; border-top: 1px solid #e5e7eb;">
                        <strong>Dibuat oleh:</strong> ${data.created_by} <br>
                        <strong>Tanggal dibuat:</strong> ${data.created_at}
                    </div>
                    ${fileHTML}
                </div>
                <div style="display: flex; gap: 1rem; justify-content: flex-end; margin-top: 2rem;">
                    <button type="button" class="btn btn-secondary" onclick="closeLihatSuratModal()">
                        Tutup
                    </button>
                </div>
            `;
            document.getElementById('lihatSuratContent').innerHTML = content;
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('lihatSuratContent').innerHTML = '<p style="color: #ef4444;">Gagal memuat data surat.</p>';
        });
}

function closeLihatSuratModal() {
    const modal = document.getElementById('lihatSuratModal');
    if (modal) modal.style.display = 'none';
}

function editSurat(suratId) {
    document.getElementById('editSuratModal').style.display = 'flex';
    
    fetch(`/surat/${suratId}/data`)
        .then(response => response.json())
        .then(data => {
            let fileHTML = '';
            if (data.file_path) {
                fileHTML = `
                    <div style="margin-bottom: 1rem; padding: 1rem; background-color: #f0f9ff; border: 1px solid #bfdbfe; border-radius: 0.5rem;">
                        <p style="color: #0c4a6e; font-size: 0.85rem; margin: 0;">
                            <strong>File saat ini:</strong>
                            <a href="${data.file_path}" target="_blank" style="color: #667eea; text-decoration: none;">
                                Lihat File
                            </a>
                        </p>
                    </div>
                `;
            }
            
            const content = `
                <form id="editSuratForm" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="form-group">
                        <label class="form-label">Nomor Surat</label>
                        <input type="text" name="nomor_surat" class="form-input" value="${data.nomor_surat}" required>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Tipe Surat</label>
                        <select name="tipe" class="form-input" required>
                            <option value="masuk" ${data.tipe === 'masuk' ? 'selected' : ''}>Surat Masuk</option>
                            <option value="keluar" ${data.tipe === 'keluar' ? 'selected' : ''}>Surat Keluar</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Perihal</label>
                        <textarea name="perihal" class="form-input" rows="3" required>${data.perihal}</textarea>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Tanggal Surat</label>
                        <input type="date" name="tanggal_surat" class="form-input" value="${data.tanggal_surat}" required>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Dari/Tujuan</label>
                        <input type="text" name="dari_tujuan" class="form-input" value="${data.dari_tujuan || ''}">
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Keterangan</label>
                        <textarea name="keterangan" class="form-input" rows="2">${data.keterangan || ''}</textarea>
                    </div>
                    
                    <div class="form-group">
                        ${fileHTML}
                        <label class="form-label">File/Dokumen Baru</label>
                        <input type="file" name="file_path" class="form-input">
                        <small style="color: #6b7280; display: block; margin-top: 0.25rem;">Format: PDF, DOC, DOCX (Max: 5MB) - Kosongkan jika tidak ingin mengganti</small>
                    </div>
                    
                    <div id="editErrorMessages" style="display: none; background-color: #fee2e2; border: 1px solid #fecaca; color: #991b1b; padding: 1rem; border-radius: 0.5rem; margin-bottom: 1rem;"></div>
                    
                    <div style="display: flex; gap: 1rem; justify-content: flex-end;">
                        <button type="button" class="btn btn-secondary" onclick="closeEditSuratModal()">
                            Batal
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Simpan Perubahan
                        </button>
                    </div>
                </form>
            `;
            document.getElementById('editSuratContent').innerHTML = content;
            
            document.getElementById('editSuratForm').addEventListener('submit', function(e) {
                e.preventDefault();
                submitEditSurat(suratId);
            });
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('editSuratContent').innerHTML = '<p style="color: #ef4444;">Gagal memuat data surat.</p>';
        });
}

function closeEditSuratModal() {
    const modal = document.getElementById('editSuratModal');
    if (modal) modal.style.display = 'none';
}

function submitEditSurat(suratId) {
    const form = document.getElementById('editSuratForm');
    const formData = new FormData(form);
    
    fetch(`/surat/${suratId}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        },
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            return response.json().then(data => {
                throw data;
            });
        }
        return response.json();
    })
    .then(data => {
        closeEditSuratModal();
        // Reload halaman untuk menampilkan data yang diperbarui
        window.location.reload();
    })
    .catch(error => {
        console.error('Error:', error);
        let errorMessage = 'Gagal menyimpan perubahan.';
        if (error.message) {
            errorMessage = error.message;
        } else if (error.errors) {
            errorMessage = Object.values(error.errors).flat().join('<br>');
        }
        document.getElementById('editErrorMessages').innerHTML = errorMessage;
        document.getElementById('editErrorMessages').style.display = 'block';
    });
}

// Close modals when clicking outside
document.addEventListener('click', function(e) {
    const lihatModal = document.getElementById('lihatSuratModal');
    const editModal = document.getElementById('editSuratModal');
    const tambahModal = document.getElementById('tambahSuratModal');
    
    if (e.target === lihatModal) {
        closeLihatSuratModal();
    }
    if (e.target === editModal) {
        closeEditSuratModal();
    }
    if (e.target === tambahModal) {
        tambahModal.style.display = 'none';
    }
});
</script>
@endsection

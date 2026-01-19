@extends('layouts.app')

@section('title', 'Anggaran - BEM Management System')
@section('page-title', 'Anggaran')

@section('content')
<div style="margin-bottom: 1.5rem; display: flex; justify-content: space-between; align-items: center;">
    <div>
        <h2 style="font-size: 1.25rem; font-weight: 600; color: #1f2937; margin-bottom: 0.25rem;">
            Manajemen Anggaran
        </h2>
        <p style="color: #6b7280; font-size: 0.9rem;">Kelola pemasukan dan pengeluaran dana BEM</p>
    </div>
    @if(Auth::user()->role === 'admin')
    <button type="button" class="btn btn-primary" onclick="document.getElementById('tambahAnggaranModal').style.display='flex'">
        <i class="fas fa-plus"></i> Tambah Transaksi
    </button>
    @endif
</div>

<!-- Statistik Keuangan -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; margin-bottom: 1.5rem;">
    @php
        $totalPemasukan = $anggaran->where('jenis', 'pemasukan')->sum('jumlah');
        $totalPengeluaran = $anggaran->where('jenis', 'pengeluaran')->sum('jumlah');
        $saldoBersih = $totalPemasukan - $totalPengeluaran;
    @endphp
    
    <!-- Kartu Pemasukan -->
    <div style="background: white; border-radius: 0.75rem; padding: 1.5rem; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05); border: 1px solid #e5e7eb; border-left: 4px solid #43e97b;">
        <div style="display: flex; justify-content: space-between; align-items: flex-start;">
            <div>
                <p style="color: #6b7280; font-size: 0.9rem; margin-bottom: 0.5rem;">
                    <i class="fas fa-arrow-down" style="color: #43e97b;"></i> Total Pemasukan
                </p>
                <h3 style="font-size: 1.875rem; font-weight: 700; color: #43e97b;">
                    Rp {{ number_format($totalPemasukan, 0, ',', '.') }}
                </h3>
            </div>
            <div style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); width: 3rem; height: 3rem; border-radius: 0.75rem; display: flex; align-items: center; justify-content: center; color: white; font-size: 1.5rem;">
                <i class="fas fa-plus"></i>
            </div>
        </div>
        <p style="color: #9ca3af; font-size: 0.85rem; margin-top: 1rem;">
            {{ $anggaran->where('jenis', 'pemasukan')->count() }} transaksi
        </p>
    </div>

    <!-- Kartu Pengeluaran -->
    <div style="background: white; border-radius: 0.75rem; padding: 1.5rem; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05); border: 1px solid #e5e7eb; border-left: 4px solid #ef4444;">
        <div style="display: flex; justify-content: space-between; align-items: flex-start;">
            <div>
                <p style="color: #6b7280; font-size: 0.9rem; margin-bottom: 0.5rem;">
                    <i class="fas fa-arrow-up" style="color: #ef4444;"></i> Total Pengeluaran
                </p>
                <h3 style="font-size: 1.875rem; font-weight: 700; color: #ef4444;">
                    Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}
                </h3>
            </div>
            <div style="background: linear-gradient(135deg, #ef4444 0%, #f87171 100%); width: 3rem; height: 3rem; border-radius: 0.75rem; display: flex; align-items: center; justify-content: center; color: white; font-size: 1.5rem;">
                <i class="fas fa-minus"></i>
            </div>
        </div>
        <p style="color: #9ca3af; font-size: 0.85rem; margin-top: 1rem;">
            {{ $anggaran->where('jenis', 'pengeluaran')->count() }} transaksi
        </p>
    </div>

    <!-- Kartu Saldo Bersih -->
    <div style="background: white; border-radius: 0.75rem; padding: 1.5rem; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05); border: 1px solid #e5e7eb; border-left: 4px solid {{ $saldoBersih >= 0 ? '#667eea' : '#ef4444' }};">
        <div style="display: flex; justify-content: space-between; align-items: flex-start;">
            <div>
                <p style="color: #6b7280; font-size: 0.9rem; margin-bottom: 0.5rem;">
                    <i class="fas fa-wallet" style="color: {{ $saldoBersih >= 0 ? '#667eea' : '#ef4444' }};"></i> Saldo Bersih
                </p>
                <h3 style="font-size: 1.875rem; font-weight: 700; color: {{ $saldoBersih >= 0 ? '#667eea' : '#ef4444' }};">
                    Rp {{ number_format(abs($saldoBersih), 0, ',', '.') }}
                </h3>
            </div>
            <div style="background: linear-gradient(135deg, {{ $saldoBersih >= 0 ? '#667eea' : '#ef4444' }} 0%, {{ $saldoBersih >= 0 ? '#764ba2' : '#f87171' }} 100%); width: 3rem; height: 3rem; border-radius: 0.75rem; display: flex; align-items: center; justify-content: center; color: white; font-size: 1.5rem;">
                <i class="fas fa-{{ $saldoBersih >= 0 ? 'arrow-up' : 'arrow-down' }}"></i>
            </div>
        </div>
        <p style="color: #9ca3af; font-size: 0.85rem; margin-top: 1rem;">
            {{ $saldoBersih >= 0 ? 'Surplus' : 'Deficit' }}
        </p>
    </div>
</div>

<!-- Filter dan Search -->
<div style="background: white; border-radius: 0.75rem; padding: 1.5rem; margin-bottom: 1.5rem; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05); border: 1px solid #e5e7eb;">
    <form method="GET" action="{{ route('anggaran') }}" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 1rem;">
        <div>
            <label style="display: block; font-weight: 500; color: #374151; font-size: 0.85rem; margin-bottom: 0.5rem;">Jenis Transaksi</label>
            <select name="jenis" class="form-input" style="font-size: 0.9rem;">
                <option value="">Semua</option>
                <option value="pemasukan" {{ request('jenis') == 'pemasukan' ? 'selected' : '' }}>Pemasukan</option>
                <option value="pengeluaran" {{ request('jenis') == 'pengeluaran' ? 'selected' : '' }}>Pengeluaran</option>
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
                <input type="text" name="search" class="form-input" placeholder="Cari keterangan..." value="{{ request('search') }}" style="font-size: 0.9rem;">
                <button type="submit" class="btn btn-primary" style="padding: 0.75rem 1rem;">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </div>
    </form>
</div>

<!-- Tabel Transaksi -->
<div style="background: white; border-radius: 0.75rem; overflow: hidden; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05); border: 1px solid #e5e7eb;">
    @if($anggaran->count() > 0)
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background-color: #f9fafb; border-bottom: 2px solid #e5e7eb;">
                    <th style="padding: 1rem; text-align: left; font-weight: 600; color: #374151; font-size: 0.85rem;">Tanggal</th>
                    <th style="padding: 1rem; text-align: left; font-weight: 600; color: #374151; font-size: 0.85rem;">Keterangan</th>
                    <th style="padding: 1rem; text-align: left; font-weight: 600; color: #374151; font-size: 0.85rem;">Jenis</th>
                    <th style="padding: 1rem; text-align: right; font-weight: 600; color: #374151; font-size: 0.85rem;">Jumlah</th>
                    <th style="padding: 1rem; text-align: left; font-weight: 600; color: #374151; font-size: 0.85rem;">PIC</th>
                    <th style="padding: 1rem; text-align: center; font-weight: 600; color: #374151; font-size: 0.85rem;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($anggaran as $item)
                <tr style="border-bottom: 1px solid #e5e7eb; {{ $loop->odd ? '' : 'background-color: #f9fafb;' }}">
                    <td style="padding: 1rem; color: #6b7280;">{{ $item->tanggal_format }}</td>
                    <td style="padding: 1rem; color: #1f2937; font-weight: 500;">{{ $item->keterangan }}</td>
                    <td style="padding: 1rem;">
                        <span style="background-color: {{ $item->jenis === 'pemasukan' ? '#d1fae5' : '#fee2e2' }}; color: {{ $item->jenis === 'pemasukan' ? '#065f46' : '#7f1d1d' }}; padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.85rem; font-weight: 500;">
                            {{ $item->jenis_label }}
                        </span>
                    </td>
                    <td style="padding: 1rem; text-align: right; font-weight: 600; color: {{ $item->jenis === 'pemasukan' ? '#43e97b' : '#ef4444' }};">{{ $item->jumlah_format }}</td>
                    <td style="padding: 1rem; color: #6b7280;">{{ $item->pic }}</td>
                    <td style="padding: 1rem; text-align: center;">
                        <button type="button" class="btn" style="background: none; color: #667eea; padding: 0.25rem 0.5rem; border: none; cursor: pointer; font-size: 0.9rem;" onclick="viewAnggaran({{ $item->id }})">
                            <i class="fas fa-eye"></i>
                        </button>
                        @if(Auth::user()->role === 'admin')
                        <button type="button" class="btn" style="background: none; color: #667eea; padding: 0.25rem 0.5rem; border: none; cursor: pointer; font-size: 0.9rem;" onclick="editAnggaran({{ $item->id }})">
                            <i class="fas fa-edit"></i>
                        </button>
                        <form action="{{ route('anggaran.destroy', $item) }}" method="POST" style="display: inline;" onsubmit="return confirm('Yakin ingin menghapus transaksi ini?');">
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
            {{ $anggaran->links() }}
        </div>
    @else
        <div style="padding: 3rem; text-align: center; color: #6b7280;">
            <i class="fas fa-receipt" style="font-size: 3rem; margin-bottom: 1rem; color: #d1d5db;"></i>
            <p>Belum ada data transaksi.</p>
        </div>
    @endif
</div>

@if(Auth::user()->role === 'admin')
<!-- Modal Tambah Transaksi -->
<div id="tambahAnggaranModal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0, 0, 0, 0.5); z-index: 1000; align-items: center; justify-content: center;">
    <div style="background: white; border-radius: 1rem; padding: 2rem; max-width: 600px; width: 90%; max-height: 90vh; overflow-y: auto; box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; border-bottom: 1px solid #e5e7eb; padding-bottom: 1rem;">
            <h3 style="font-size: 1.25rem; font-weight: 600; color: #1f2937; margin: 0;">Tambah Transaksi Baru</h3>
            <button type="button" onclick="document.getElementById('tambahAnggaranModal').style.display='none'" style="background: none; border: none; cursor: pointer; color: #6b7280; font-size: 1.5rem;">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <form action="{{ route('anggaran.store') }}" method="POST">
            @csrf
            
            <div class="form-group">
                <label class="form-label">Tanggal Transaksi</label>
                <input type="date" name="tanggal" class="form-input @error('tanggal') border-red-500 @enderror" required>
                @error('tanggal')
                    <small style="color: #ef4444;">{{ $message }}</small>
                @enderror
            </div>
            
            <div class="form-group">
                <label class="form-label">Jenis Transaksi</label>
                <select name="jenis" class="form-input @error('jenis') border-red-500 @enderror" required>
                    <option value="">-- Pilih --</option>
                    <option value="pemasukan">Pemasukan</option>
                    <option value="pengeluaran">Pengeluaran</option>
                </select>
                @error('jenis')
                    <small style="color: #ef4444;">{{ $message }}</small>
                @enderror
            </div>
            
            <div class="form-group">
                <label class="form-label">Keterangan</label>
                <input type="text" name="keterangan" class="form-input @error('keterangan') border-red-500 @enderror" placeholder="Contoh: Iuran Anggota, Biaya Acara, dll" required>
                @error('keterangan')
                    <small style="color: #ef4444;">{{ $message }}</small>
                @enderror
            </div>
            
            <div class="form-group">
                <label class="form-label">Jumlah (Rp)</label>
                <input type="number" name="jumlah" class="form-input @error('jumlah') border-red-500 @enderror" placeholder="0" step="1000" required>
                @error('jumlah')
                    <small style="color: #ef4444;">{{ $message }}</small>
                @enderror
            </div>
            
            <div class="form-group">
                <label class="form-label">PIC (Penanggung Jawab)</label>
                <input type="text" name="pic" class="form-input @error('pic') border-red-500 @enderror" placeholder="Nama penanggung jawab..." required>
                @error('pic')
                    <small style="color: #ef4444;">{{ $message }}</small>
                @enderror
            </div>
            
            <div class="form-group">
                <label class="form-label">Catatan</label>
                <textarea name="catatan" class="form-input @error('catatan') border-red-500 @enderror" rows="3" placeholder="Catatan tambahan..."></textarea>
                @error('catatan')
                    <small style="color: #ef4444;">{{ $message }}</small>
                @enderror
            </div>
            
            <div style="display: flex; gap: 1rem; justify-content: flex-end;">
                <button type="button" class="btn btn-secondary" onclick="document.getElementById('tambahAnggaranModal').style.display='none'">
                    Batal
                </button>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Simpan Transaksi
                </button>
            </div>
        </form>
    </div>
</div>
@endif

<!-- Modal Lihat Anggaran -->
<div id="lihatAnggaranModal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0, 0, 0, 0.5); z-index: 1000; align-items: center; justify-content: center;">
    <div style="background: white; border-radius: 1rem; padding: 2rem; max-width: 600px; width: 90%; max-height: 90vh; overflow-y: auto; box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; border-bottom: 1px solid #e5e7eb; padding-bottom: 1rem;">
            <h3 style="font-size: 1.25rem; font-weight: 600; color: #1f2937; margin: 0;">Detail Transaksi</h3>
            <button type="button" onclick="closeLihatAnggaranModal()" style="background: none; border: none; cursor: pointer; color: #6b7280; font-size: 1.5rem;">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <div id="lihatAnggaranContent">
            <div style="text-align: center; padding: 2rem;">
                <i class="fas fa-spinner fa-spin" style="font-size: 2rem; color: #667eea;"></i>
                <p style="color: #6b7280; margin-top: 1rem;">Memuat data...</p>
            </div>
        </div>
    </div>
</div>

<!-- Modal Edit Anggaran -->
@if(Auth::user()->role === 'admin')
<div id="editAnggaranModal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0, 0, 0, 0.5); z-index: 1000; align-items: center; justify-content: center;">
    <div style="background: white; border-radius: 1rem; padding: 2rem; max-width: 600px; width: 90%; max-height: 90vh; overflow-y: auto; box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; border-bottom: 1px solid #e5e7eb; padding-bottom: 1rem;">
            <h3 style="font-size: 1.25rem; font-weight: 600; color: #1f2937; margin: 0;">Edit Transaksi</h3>
            <button type="button" onclick="closeEditAnggaranModal()" style="background: none; border: none; cursor: pointer; color: #6b7280; font-size: 1.5rem;">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <div id="editAnggaranContent">
            <div style="text-align: center; padding: 2rem;">
                <i class="fas fa-spinner fa-spin" style="font-size: 2rem; color: #667eea;"></i>
                <p style="color: #6b7280; margin-top: 1rem;">Memuat data...</p>
            </div>
        </div>
    </div>
</div>
@endif

<script>
function viewAnggaran(anggaranId) {
    document.getElementById('lihatAnggaranModal').style.display = 'flex';
    
    fetch(`/anggaran/${anggaranId}/data`)
        .then(response => response.json())
        .then(data => {
            const content = `
                <div style="margin-bottom: 1rem;">
                    <div style="margin-bottom: 1rem;">
                        <strong style="color: #374151;">Tanggal:</strong>
                        <p style="color: #1f2937; margin: 0.25rem 0 0 0; font-size: 0.95rem;">${data.tanggal_format}</p>
                    </div>
                    <div style="margin-bottom: 1rem;">
                        <strong style="color: #374151;">Jenis Transaksi:</strong>
                        <p style="margin: 0.25rem 0 0 0;">
                            <span style="background-color: ${data.jenis === 'pemasukan' ? '#d1fae5' : '#fee2e2'}; color: ${data.jenis === 'pemasukan' ? '#065f46' : '#7f1d1d'}; padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.85rem; font-weight: 500;">
                                ${data.jenis_label}
                            </span>
                        </p>
                    </div>
                    <div style="margin-bottom: 1rem;">
                        <strong style="color: #374151;">Keterangan:</strong>
                        <p style="color: #1f2937; margin: 0.25rem 0 0 0; font-size: 0.95rem;">${data.keterangan}</p>
                    </div>
                    <div style="margin-bottom: 1rem;">
                        <strong style="color: #374151;">Jumlah:</strong>
                        <p style="margin: 0.25rem 0 0 0;">
                            <span style="font-size: 0.95rem; font-weight: 600; color: ${data.jenis === 'pemasukan' ? '#43e97b' : '#ef4444'};">
                                ${data.jumlah_format}
                            </span>
                        </p>
                    </div>
                    <div style="margin-bottom: 1rem;">
                        <strong style="color: #374151;">PIC:</strong>
                        <p style="color: #1f2937; margin: 0.25rem 0 0 0; font-size: 0.95rem;">${data.pic}</p>
                    </div>
                    <div style="margin-bottom: 1rem;">
                        <strong style="color: #374151;">Catatan:</strong>
                        <p style="color: #1f2937; margin: 0.25rem 0 0 0; font-size: 0.95rem;">${data.catatan || '-'}</p>
                    </div>
                    <div style="color: #6b7280; font-size: 0.85rem; margin-top: 1rem; padding-top: 1rem; border-top: 1px solid #e5e7eb;">
                        <strong>Dibuat oleh:</strong> ${data.created_by} <br>
                        <strong>Tanggal dibuat:</strong> ${data.created_at}
                    </div>
                </div>
                <div style="display: flex; gap: 1rem; justify-content: flex-end; margin-top: 2rem;">
                    <button type="button" class="btn btn-secondary" onclick="closeLihatAnggaranModal()">
                        Tutup
                    </button>
                </div>
            `;
            document.getElementById('lihatAnggaranContent').innerHTML = content;
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('lihatAnggaranContent').innerHTML = '<p style="color: #ef4444;">Gagal memuat data transaksi.</p>';
        });
}

function closeLihatAnggaranModal() {
    const modal = document.getElementById('lihatAnggaranModal');
    if (modal) modal.style.display = 'none';
}

function editAnggaran(anggaranId) {
    document.getElementById('editAnggaranModal').style.display = 'flex';
    
    fetch(`/anggaran/${anggaranId}/data`)
        .then(response => response.json())
        .then(data => {
            const content = `
                <form id="editAnggaranForm" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="form-group">
                        <label class="form-label">Tanggal Transaksi</label>
                        <input type="date" name="tanggal" class="form-input" value="${data.tanggal}" required>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Jenis Transaksi</label>
                        <select name="jenis" class="form-input" required>
                            <option value="pemasukan" ${data.jenis === 'pemasukan' ? 'selected' : ''}>Pemasukan</option>
                            <option value="pengeluaran" ${data.jenis === 'pengeluaran' ? 'selected' : ''}>Pengeluaran</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Keterangan</label>
                        <input type="text" name="keterangan" class="form-input" value="${data.keterangan}" required>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Jumlah (Rp)</label>
                        <input type="number" name="jumlah" class="form-input" value="${data.jumlah}" step="1000" required>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">PIC (Penanggung Jawab)</label>
                        <input type="text" name="pic" class="form-input" value="${data.pic}" required>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Catatan</label>
                        <textarea name="catatan" class="form-input" rows="3">${data.catatan || ''}</textarea>
                    </div>
                    
                    <div id="editErrorMessages" style="display: none; background-color: #fee2e2; border: 1px solid #fecaca; color: #991b1b; padding: 1rem; border-radius: 0.5rem; margin-bottom: 1rem;"></div>
                    
                    <div style="display: flex; gap: 1rem; justify-content: flex-end;">
                        <button type="button" class="btn btn-secondary" onclick="closeEditAnggaranModal()">
                            Batal
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Simpan Perubahan
                        </button>
                    </div>
                </form>
            `;
            document.getElementById('editAnggaranContent').innerHTML = content;
            
            document.getElementById('editAnggaranForm').addEventListener('submit', function(e) {
                e.preventDefault();
                submitEditAnggaran(anggaranId);
            });
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('editAnggaranContent').innerHTML = '<p style="color: #ef4444;">Gagal memuat data transaksi.</p>';
        });
}

function closeEditAnggaranModal() {
    const modal = document.getElementById('editAnggaranModal');
    if (modal) modal.style.display = 'none';
}

function submitEditAnggaran(anggaranId) {
    const form = document.getElementById('editAnggaranForm');
    const formData = new FormData(form);
    
    fetch(`/anggaran/${anggaranId}`, {
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
        closeEditAnggaranModal();
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
    const lihatModal = document.getElementById('lihatAnggaranModal');
    const editModal = document.getElementById('editAnggaranModal');
    const tambahModal = document.getElementById('tambahAnggaranModal');
    
    if (e.target === lihatModal) {
        closeLihatAnggaranModal();
    }
    if (e.target === editModal) {
        closeEditAnggaranModal();
    }
    if (e.target === tambahModal) {
        tambahModal.style.display = 'none';
    }
});
</script>
@endsection

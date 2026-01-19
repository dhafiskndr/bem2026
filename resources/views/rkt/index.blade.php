@extends('layouts.app')

@section('title', 'RKT BEM - BEM Management System')
@section('page-title', 'RKT BEM')

@section('content')
<div style="margin-bottom: 1.5rem; display: flex; justify-content: space-between; align-items: center;">
    <div>
        <h2 style="font-size: 1.25rem; font-weight: 600; color: #1f2937; margin-bottom: 0.25rem;">
            Rencana Kerja Tahunan (RKT)
        </h2>
        <p style="color: #6b7280; font-size: 0.9rem;">Kelola program dan kegiatan tahunan BEM</p>
    </div>
    <div style="display: flex; gap: 0.5rem;">
        <button type="button" class="btn btn-secondary" onclick="window.location.href='{{ route('rkt.export.pdf') }}'">
            <i class="fas fa-file-pdf"></i> Export PDF
        </button>
        @if(Auth::user()->role === 'admin')
        <button type="button" class="btn btn-primary" onclick="document.getElementById('tambahRKTModal').style.display='flex'">
            <i class="fas fa-plus"></i> Tambah Program
        </button>
        @endif
    </div>
</div>

<!-- Gantt Chart Mini -->
<div style="background: white; border-radius: 0.75rem; padding: 1.5rem; margin-bottom: 1.5rem; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05); border: 1px solid #e5e7eb; overflow-x: auto;">
    <h3 style="font-size: 1rem; font-weight: 600; color: #1f2937; margin-bottom: 1rem;">
        <i class="fas fa-chart-gantt"></i> Timeline Program Tahunan 2026
    </h3>
    <div style="display: flex; gap: 1rem; overflow-x: auto; padding-bottom: 1rem;">
        @php
            $months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        @endphp
        
        @foreach($rkt as $program)
        <div style="flex-shrink: 0; min-width: 300px;">
            <div style="font-weight: 600; color: #1f2937; margin-bottom: 0.5rem; font-size: 0.9rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                {{ $program->nama_program }}
            </div>
            <div style="display: grid; grid-template-columns: repeat(12, 1fr); gap: 2px; background: #e5e7eb; padding: 0.25rem; border-radius: 0.25rem;">
                @php
                    $mulaiMonth = $program->tanggal_mulai->month;
                    $selesaiMonth = $program->tanggal_selesai->month;
                @endphp
                @for ($m = 1; $m <= 12; $m++)
                    @php
                        $isActive = $m >= $mulaiMonth && $m <= $selesaiMonth;
                    @endphp
                    <div style="background: {{ $isActive ? $program->divisi_color : '#f3f4f6' }}; height: 20px; border-radius: 0.125rem;" title="{{ $isActive ? $months[$m-1] : '' }}"></div>
                @endfor
            </div>
            <div style="font-size: 0.75rem; color: #6b7280; margin-top: 0.25rem;">
                {{ $program->tanggal_mulai->format('M Y') }} - {{ $program->tanggal_selesai->format('M Y') }}
            </div>
        </div>
        @endforeach
    </div>
</div>

<!-- Filter -->
<div style="background: white; border-radius: 0.75rem; padding: 1.5rem; margin-bottom: 1.5rem; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05); border: 1px solid #e5e7eb;">
    <form method="GET" action="{{ route('rkt') }}" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 1rem;">
        <div>
            <label style="display: block; font-weight: 500; color: #374151; font-size: 0.85rem; margin-bottom: 0.5rem;">Status</label>
            <select name="status" class="form-input" style="font-size: 0.9rem;">
                <option value="">Semua</option>
                <option value="belum" {{ request('status') == 'belum' ? 'selected' : '' }}>Belum Dimulai</option>
                <option value="berjalan" {{ request('status') == 'berjalan' ? 'selected' : '' }}>Sedang Berjalan</option>
                <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
            </select>
        </div>
        <div>
            <label style="display: block; font-weight: 500; color: #374151; font-size: 0.85rem; margin-bottom: 0.5rem;">Divisi</label>
            <select name="divisi" class="form-input" style="font-size: 0.9rem;">
                <option value="">Semua Divisi</option>
                <option value="agama" {{ request('divisi') == 'agama' ? 'selected' : '' }}>Dept. Agama</option>
                <option value="dageri" {{ request('divisi') == 'dageri' ? 'selected' : '' }}>Dept. Dageri</option>
                <option value="minba" {{ request('divisi') == 'minba' ? 'selected' : '' }}>Dept. Minba</option>
                <option value="sospen" {{ request('divisi') == 'sospen' ? 'selected' : '' }}>Dept. Sospen</option>
                <option value="kominfo" {{ request('divisi') == 'kominfo' ? 'selected' : '' }}>Dept. Kominfo</option>
            </select>
        </div>
        <div>
            <label style="display: block; font-weight: 500; color: #374151; font-size: 0.85rem; margin-bottom: 0.5rem;">Cari</label>
            <div style="display: flex; gap: 0.5rem;">
                <input type="text" name="search" class="form-input" placeholder="Cari nama program..." value="{{ request('search') }}" style="font-size: 0.9rem;">
                <button type="submit" class="btn btn-primary" style="padding: 0.75rem 1rem;">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </div>
    </form>
</div>

<!-- Statistics -->
@php
    $totalProgram = $rkt->total();
    $programBerjalan = $rkt->where('status', 'berjalan')->count();
    $programSelesai = $rkt->where('status', 'selesai')->count();
@endphp
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem; margin-bottom: 1.5rem;">
    <div style="background: white; border-radius: 0.75rem; padding: 1.5rem; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05); border: 1px solid #e5e7eb; border-left: 4px solid #667eea;">
        <div style="color: #6b7280; font-size: 0.9rem; margin-bottom: 0.5rem;">Total Program</div>
        <div style="font-size: 1.75rem; font-weight: 700; color: #667eea;">{{ $totalProgram }}</div>
    </div>
    <div style="background: white; border-radius: 0.75rem; padding: 1.5rem; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05); border: 1px solid #e5e7eb; border-left: 4px solid #667eea;">
        <div style="color: #6b7280; font-size: 0.9rem; margin-bottom: 0.5rem;">Sedang Berjalan</div>
        <div style="font-size: 1.75rem; font-weight: 700; color: #667eea;">{{ $programBerjalan }}</div>
    </div>
    <div style="background: white; border-radius: 0.75rem; padding: 1.5rem; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05); border: 1px solid #e5e7eb; border-left: 4px solid #43e97b;">
        <div style="color: #6b7280; font-size: 0.9rem; margin-bottom: 0.5rem;">Selesai</div>
        <div style="font-size: 1.75rem; font-weight: 700; color: #43e97b;">{{ $programSelesai }}</div>
    </div>
</div>

<!-- Daftar Program -->
<div style="background: white; border-radius: 0.75rem; overflow: hidden; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05); border: 1px solid #e5e7eb;">
    @if($rkt->count() > 0)
    <table style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr style="background-color: #f9fafb; border-bottom: 2px solid #e5e7eb;">
                <th style="padding: 1rem; text-align: left; font-weight: 600; color: #374151; font-size: 0.85rem;">Program/Kegiatan</th>
                <th style="padding: 1rem; text-align: left; font-weight: 600; color: #374151; font-size: 0.85rem;">Divisi</th>
                <th style="padding: 1rem; text-align: left; font-weight: 600; color: #374151; font-size: 0.85rem;">Durasi</th>
                <th style="padding: 1rem; text-align: left; font-weight: 600; color: #374151; font-size: 0.85rem;">Status</th>
                <th style="padding: 1rem; text-align: center; font-weight: 600; color: #374151; font-size: 0.85rem;">Progress</th>
                <th style="padding: 1rem; text-align: center; font-weight: 600; color: #374151; font-size: 0.85rem;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($rkt as $item)
            <tr style="border-bottom: 1px solid #e5e7eb; {{ $loop->odd ? '' : 'background-color: #f9fafb;' }}">
                <td style="padding: 1rem; color: #1f2937; font-weight: 500;">
                    <div style="margin-bottom: 0.25rem;">{{ $item->nama_program }}</div>
                    <div style="font-size: 0.85rem; color: #6b7280;">{{ $item->pic }}</div>
                </td>
                <td style="padding: 1rem;">
                    <span style="background-color: {{ $item->divisi_color }}22; color: {{ $item->divisi_color }}; padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.85rem; font-weight: 500;">
                        {{ $item->divisi_label }}
                    </span>
                </td>
                <td style="padding: 1rem; color: #6b7280; font-size: 0.9rem;">
                    {{ $item->tanggal_mulai_format }}<br>
                    s.d {{ $item->tanggal_selesai_format }}
                </td>
                <td style="padding: 1rem;">
                    <span style="background-color: {{ $item->status_bg_color }}; color: {{ $item->status_color }}; padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.85rem; font-weight: 500;">
                        {{ $item->status_label }}
                    </span>
                </td>
                <td style="padding: 1rem;">
                    <div style="background: #e5e7eb; border-radius: 9999px; height: 6px; overflow: hidden;">
                        <div style="background: {{ $item->status_color }}; height: 100%; width: {{ $item->progress_percent }}%;"></div>
                    </div>
                    <div style="font-size: 0.75rem; color: #6b7280; margin-top: 0.25rem; text-align: center;">{{ $item->progress_percent }}%</div>
                </td>
                <td style="padding: 1rem; text-align: center;">
                    <button type="button" class="btn" style="background: none; color: #667eea; padding: 0.25rem 0.5rem; border: none; cursor: pointer; font-size: 0.9rem;" onclick="viewRkt({{ $item->id }})">
                        <i class="fas fa-eye"></i>
                    </button>
                    @if(Auth::user()->role === 'admin')
                    <button type="button" class="btn" style="background: none; color: #667eea; padding: 0.25rem 0.5rem; border: none; cursor: pointer; font-size: 0.9rem;" onclick="editRkt({{ $item->id }})">
                        <i class="fas fa-edit"></i>
                    </button>
                    <form action="{{ route('rkt.destroy', $item) }}" method="POST" style="display: inline;" onsubmit="return confirm('Yakin ingin menghapus program ini?');">
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
    @else
    <div style="padding: 3rem; text-align: center; color: #6b7280;">
        <i class="fas fa-inbox" style="font-size: 3rem; margin-bottom: 1rem; opacity: 0.5;"></i>
        <p>Belum ada program. Silakan tambahkan program baru.</p>
    </div>
    @endif
</div>

<!-- Pagination -->
@if($rkt->hasPages())
<div style="margin-top: 1.5rem; display: flex; justify-content: center;">
    {{ $rkt->links() }}
</div>
@endif

@if(Auth::user()->role === 'admin')
<!-- Modal Tambah Program RKT -->
<div id="tambahRKTModal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0, 0, 0, 0.5); z-index: 1000; align-items: center; justify-content: center;">
    <div style="background: white; border-radius: 1rem; padding: 2rem; max-width: 650px; width: 90%; max-height: 90vh; overflow-y: auto; box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; border-bottom: 1px solid #e5e7eb; padding-bottom: 1rem;">
            <h3 style="font-size: 1.25rem; font-weight: 600; color: #1f2937; margin: 0;">Tambah Program RKT</h3>
            <button type="button" onclick="closeRktModals()" style="background: none; border: none; cursor: pointer; color: #6b7280; font-size: 1.5rem;">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <form id="formTambahRkt" onsubmit="submitFormRkt(event)">
            @csrf
            <div class="form-group">
                <label class="form-label">Nama Program/Kegiatan *</label>
                <input type="text" name="nama_program" class="form-input" placeholder="Masukkan nama program..." required>
            </div>
            
            <div class="form-group">
                <label class="form-label">Divisi *</label>
                <select name="divisi" class="form-input" required>
                    <option value="">-- Pilih --</option>
                    <option value="agama">Dept. Agama</option>
                    <option value="dageri">Dept. Dageri</option>
                    <option value="minba">Dept. Minba</option>
                    <option value="sospen">Dept. Sospen</option>
                    <option value="kominfo">Dept. Kominfo</option>
                </select>
            </div>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div class="form-group">
                    <label class="form-label">Tanggal Mulai *</label>
                    <input type="date" name="tanggal_mulai" class="form-input" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Tanggal Selesai *</label>
                    <input type="date" name="tanggal_selesai" class="form-input" required>
                </div>
            </div>
            
            <div class="form-group">
                <label class="form-label">Lokasi</label>
                <input type="text" name="lokasi" class="form-input" placeholder="Lokasi kegiatan...">
            </div>
            
            <div class="form-group">
                <label class="form-label">Deskripsi Program</label>
                <textarea name="deskripsi" class="form-input" rows="3" placeholder="Deskripsi program..."></textarea>
            </div>
            
            <div class="form-group">
                <label class="form-label">Target/Tujuan</label>
                <textarea name="tujuan" class="form-input" rows="2" placeholder="Target yang ingin dicapai..."></textarea>
            </div>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div class="form-group">
                    <label class="form-label">Target Peserta</label>
                    <input type="number" name="target_peserta" class="form-input" placeholder="Jumlah peserta" min="0">
                </div>
                <div class="form-group">
                    <label class="form-label">Anggaran (Rp) *</label>
                    <input type="number" name="anggaran" class="form-input" placeholder="Jumlah anggaran" min="0" required>
                </div>
            </div>
            
            <div class="form-group">
                <label class="form-label">PIC (Penanggung Jawab) *</label>
                <input type="text" name="pic" class="form-input" placeholder="Nama penanggung jawab..." required>
            </div>
            
            <div id="formErrorRkt" style="display: none; background: #fee2e2; color: #7f1d1d; padding: 1rem; border-radius: 0.5rem; margin-bottom: 1rem;"></div>
            
            <div style="display: flex; gap: 1rem; justify-content: flex-end;">
                <button type="button" class="btn btn-secondary" onclick="closeRktModals()">
                    Batal
                </button>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Simpan Program
                </button>
            </div>
        </form>
    </div>
</div>
@endif

<!-- Modal View Program RKT -->
<div id="viewRKTModal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0, 0, 0, 0.5); z-index: 1000; align-items: center; justify-content: center;">
    <div style="background: white; border-radius: 1rem; padding: 2rem; max-width: 650px; width: 90%; max-height: 90vh; overflow-y: auto; box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; border-bottom: 1px solid #e5e7eb; padding-bottom: 1rem;">
            <h3 style="font-size: 1.25rem; font-weight: 600; color: #1f2937; margin: 0;">Detail Program</h3>
            <button type="button" onclick="closeRktModals()" style="background: none; border: none; cursor: pointer; color: #6b7280; font-size: 1.5rem;">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <div id="viewRktContent"></div>
    </div>
</div>

<!-- Modal Edit Program RKT -->
@if(Auth::user()->role === 'admin')
<div id="editRKTModal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0, 0, 0, 0.5); z-index: 1000; align-items: center; justify-content: center;">
    <div style="background: white; border-radius: 1rem; padding: 2rem; max-width: 650px; width: 90%; max-height: 90vh; overflow-y: auto; box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; border-bottom: 1px solid #e5e7eb; padding-bottom: 1rem;">
            <h3 style="font-size: 1.25rem; font-weight: 600; color: #1f2937; margin: 0;">Edit Program RKT</h3>
            <button type="button" onclick="closeRktModals()" style="background: none; border: none; cursor: pointer; color: #6b7280; font-size: 1.5rem;">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <form id="formEditRkt" onsubmit="submitFormEditRkt(event)">
            @csrf
            @method('PUT')
            <input type="hidden" id="editRktId" name="id">
            
            <div class="form-group">
                <label class="form-label">Nama Program/Kegiatan *</label>
                <input type="text" id="editNamaProgram" name="nama_program" class="form-input" required>
            </div>
            
            <div class="form-group">
                <label class="form-label">Divisi *</label>
                <select id="editDivisi" name="divisi" class="form-input" required>
                    <option value="">-- Pilih --</option>
                    <option value="agama">Dept. Agama</option>
                    <option value="dageri">Dept. Dageri</option>
                    <option value="minba">Dept. Minba</option>
                    <option value="sospen">Dept. Sospen</option>
                    <option value="kominfo">Dept. Kominfo</option>
                </select>
            </div>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div class="form-group">
                    <label class="form-label">Tanggal Mulai *</label>
                    <input type="date" id="editTanggalMulai" name="tanggal_mulai" class="form-input" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Tanggal Selesai *</label>
                    <input type="date" id="editTanggalSelesai" name="tanggal_selesai" class="form-input" required>
                </div>
            </div>
            
            <div class="form-group">
                <label class="form-label">Lokasi</label>
                <input type="text" id="editLokasi" name="lokasi" class="form-input">
            </div>
            
            <div class="form-group">
                <label class="form-label">Deskripsi Program</label>
                <textarea id="editDeskripsi" name="deskripsi" class="form-input" rows="3"></textarea>
            </div>
            
            <div class="form-group">
                <label class="form-label">Target/Tujuan</label>
                <textarea id="editTujuan" name="tujuan" class="form-input" rows="2"></textarea>
            </div>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div class="form-group">
                    <label class="form-label">Target Peserta</label>
                    <input type="number" id="editTargetPeserta" name="target_peserta" class="form-input" min="0">
                </div>
                <div class="form-group">
                    <label class="form-label">Anggaran (Rp) *</label>
                    <input type="number" id="editAnggaran" name="anggaran" class="form-input" min="0" required>
                </div>
            </div>
            
            <div class="form-group">
                <label class="form-label">PIC (Penanggung Jawab) *</label>
                <input type="text" id="editPic" name="pic" class="form-input" required>
            </div>
            
            <div class="form-group">
                <label class="form-label">Status</label>
                <select id="editStatus" name="status" class="form-input">
                    <option value="belum">Belum Dimulai</option>
                    <option value="berjalan">Sedang Berjalan</option>
                    <option value="selesai">Selesai</option>
                </select>
            </div>
            
            <div id="formErrorEditRkt" style="display: none; background: #fee2e2; color: #7f1d1d; padding: 1rem; border-radius: 0.5rem; margin-bottom: 1rem;"></div>
            
            <div style="display: flex; gap: 1rem; justify-content: flex-end;">
                <button type="button" class="btn btn-secondary" onclick="closeRktModals()">
                    Batal
                </button>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Perbarui Program
                </button>
            </div>
        </form>
    </div>
</div>
@endif

<script>
function closeRktModals() {
    const tambahModal = document.getElementById('tambahRKTModal');
    const viewModal = document.getElementById('viewRKTModal');
    const editModal = document.getElementById('editRKTModal');
    
    if (tambahModal) tambahModal.style.display = 'none';
    if (viewModal) viewModal.style.display = 'none';
    if (editModal) editModal.style.display = 'none';
}

function viewRkt(id) {
    fetch(`/rkt/${id}/data`)
        .then(response => response.json())
        .then(data => {
            let html = `
                <div style="display: grid; gap: 1rem;">
                    <div>
                        <div style="font-weight: 600; color: #6b7280; font-size: 0.85rem;">Nama Program</div>
                        <div style="color: #1f2937; font-weight: 500;">${data.nama_program}</div>
                    </div>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                        <div>
                            <div style="font-weight: 600; color: #6b7280; font-size: 0.85rem;">Divisi</div>
                            <div style="color: #1f2937; font-weight: 500;">${data.divisi_label}</div>
                        </div>
                        <div>
                            <div style="font-weight: 600; color: #6b7280; font-size: 0.85rem;">Status</div>
                            <div style="background-color: ${data.status_color}22; color: ${data.status_color}; padding: 0.25rem 0.75rem; border-radius: 9999px; font-weight: 500; display: inline-block; font-size: 0.85rem;">${data.status_label}</div>
                        </div>
                    </div>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                        <div>
                            <div style="font-weight: 600; color: #6b7280; font-size: 0.85rem;">Tanggal Mulai</div>
                            <div style="color: #1f2937;">${data.tanggal_mulai_format}</div>
                        </div>
                        <div>
                            <div style="font-weight: 600; color: #6b7280; font-size: 0.85rem;">Tanggal Selesai</div>
                            <div style="color: #1f2937;">${data.tanggal_selesai_format}</div>
                        </div>
                    </div>
                    <div>
                        <div style="font-weight: 600; color: #6b7280; font-size: 0.85rem;">Progress</div>
                        <div style="display: flex; align-items: center; gap: 1rem;">
                            <div style="flex: 1; background: #e5e7eb; border-radius: 9999px; height: 8px; overflow: hidden;">
                                <div style="background: ${data.status_color}; height: 100%; width: ${data.progress}%;"></div>
                            </div>
                            <span style="color: #1f2937; font-weight: 600; min-width: 40px;">${data.progress}%</span>
                        </div>
                    </div>
                    <div>
                        <div style="font-weight: 600; color: #6b7280; font-size: 0.85rem;">Deskripsi</div>
                        <div style="color: #1f2937;">${data.deskripsi || '-'}</div>
                    </div>
                    <div>
                        <div style="font-weight: 600; color: #6b7280; font-size: 0.85rem;">Tujuan</div>
                        <div style="color: #1f2937;">${data.tujuan || '-'}</div>
                    </div>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                        <div>
                            <div style="font-weight: 600; color: #6b7280; font-size: 0.85rem;">PIC</div>
                            <div style="color: #1f2937;">${data.pic}</div>
                        </div>
                        <div>
                            <div style="font-weight: 600; color: #6b7280; font-size: 0.85rem;">Target Peserta</div>
                            <div style="color: #1f2937;">${data.target_peserta || '-'}</div>
                        </div>
                    </div>
                    <div>
                        <div style="font-weight: 600; color: #6b7280; font-size: 0.85rem;">Lokasi</div>
                        <div style="color: #1f2937;">${data.lokasi || '-'}</div>
                    </div>
                    <div>
                        <div style="font-weight: 600; color: #6b7280; font-size: 0.85rem;">Anggaran</div>
                        <div style="color: #1f2937; font-weight: 600;">${data.anggaran_format}</div>
                    </div>
                </div>
            `;
            document.getElementById('viewRktContent').innerHTML = html;
            document.getElementById('viewRKTModal').style.display = 'flex';
        });
}

function editRkt(id) {
    fetch(`/rkt/${id}/data`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('editRktId').value = data.id;
            document.getElementById('editNamaProgram').value = data.nama_program;
            document.getElementById('editDivisi').value = data.divisi;
            document.getElementById('editTanggalMulai').value = data.tanggal_mulai;
            document.getElementById('editTanggalSelesai').value = data.tanggal_selesai;
            document.getElementById('editLokasi').value = data.lokasi || '';
            document.getElementById('editDeskripsi').value = data.deskripsi || '';
            document.getElementById('editTujuan').value = data.tujuan || '';
            document.getElementById('editTargetPeserta').value = data.target_peserta || '';
            document.getElementById('editAnggaran').value = data.anggaran;
            document.getElementById('editPic').value = data.pic;
            document.getElementById('editStatus').value = data.status;
            document.getElementById('editRKTModal').style.display = 'flex';
        });
}

function submitFormRkt(e) {
    e.preventDefault();
    const formData = new FormData(document.getElementById('formTambahRkt'));
    
    fetch('{{ route('rkt.store') }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: formData
    })
    .then(response => {
        // Check if response is JSON
        const contentType = response.headers.get('content-type');
        if (!contentType || !contentType.includes('application/json')) {
            throw new Error('Server returned HTML instead of JSON. Status: ' + response.status);
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            // Show success toast
            showSuccessToast(data.message || 'Program berhasil ditambahkan');
            closeRktModals();
            // Reload after showing toast
            setTimeout(() => location.reload(), 1500);
        } else {
            document.getElementById('formErrorRkt').innerHTML = data.message;
            document.getElementById('formErrorRkt').style.display = 'block';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        document.getElementById('formErrorRkt').innerHTML = 'Terjadi kesalahan: ' + error.message;
        document.getElementById('formErrorRkt').style.display = 'block';
    });
}

function submitFormEditRkt(e) {
    e.preventDefault();
    const id = document.getElementById('editRktId').value;
    const formData = new FormData(document.getElementById('formEditRkt'));
    
    fetch(`/rkt/${id}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        },
        body: formData
    })
    .then(response => {
        // Check if response is JSON
        const contentType = response.headers.get('content-type');
        if (!contentType || !contentType.includes('application/json')) {
            throw new Error('Server returned HTML instead of JSON. Status: ' + response.status);
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            // Show success toast
            showSuccessToast(data.message || 'Program berhasil diperbarui');
            closeRktModals();
            // Reload after showing toast
            setTimeout(() => location.reload(), 1500);
        } else {
            document.getElementById('formErrorEditRkt').innerHTML = data.message;
            document.getElementById('formErrorEditRkt').style.display = 'block';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        document.getElementById('formErrorEditRkt').innerHTML = 'Terjadi kesalahan: ' + error.message;
        document.getElementById('formErrorEditRkt').style.display = 'block';
    });
}

function showSuccessToast(message) {
    // Create toast element
    const toast = document.createElement('div');
    toast.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
        color: white;
        padding: 1rem 1.5rem;
        border-radius: 0.5rem;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        z-index: 9999;
        font-weight: 500;
        animation: slideIn 0.3s ease;
    `;
    toast.textContent = message;
    
    document.body.appendChild(toast);
    
    // Remove after 3 seconds
    setTimeout(() => {
        toast.style.animation = 'slideOut 0.3s ease';
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}

// Add CSS animations
const style = document.createElement('style');
style.textContent = `
    @keyframes slideIn {
        from {
            transform: translateX(400px);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    @keyframes slideOut {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(400px);
            opacity: 0;
        }
    }
`;
document.head.appendChild(style);
</script>
@endsection

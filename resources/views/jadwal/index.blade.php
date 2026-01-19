@extends('layouts.app')

@section('title', 'Jadwal Kegiatan - BEM Management System')
@section('page-title', 'Jadwal Kegiatan')

@section('content')
<div style="margin-bottom: 1.5rem; display: flex; justify-content: space-between; align-items: center;">
    <div>
        <h2 style="font-size: 1.25rem; font-weight: 600; color: #1f2937; margin-bottom: 0.25rem;">
            Manajemen Jadwal
        </h2>
        <p style="color: #6b7280; font-size: 0.9rem;">Kelola jadwal kegiatan dan acara BEM</p>
    </div>
    @if(Auth::user()->role === 'admin')
    <button type="button" class="btn btn-primary" onclick="document.getElementById('tambahJadwalModal').style.display='flex'">
        <i class="fas fa-plus"></i> Tambah Jadwal
    </button>
    @endif
</div>

<!-- Tampilan Kalender -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem; margin-bottom: 1.5rem;">
    <!-- Mini Kalender -->
    <div style="background: white; border-radius: 0.75rem; padding: 1.5rem; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05); border: 1px solid #e5e7eb;">
        <h3 style="font-size: 1rem; font-weight: 600; color: #1f2937; margin-bottom: 1rem; text-align: center;">
            {{ now()->translatedFormat('F Y') }}
        </h3>
        <table style="width: 100%; text-align: center; font-size: 0.85rem;">
            <tr>
                <td style="padding: 0.5rem; color: #667eea; font-weight: 600;">Min</td>
                <td style="padding: 0.5rem; color: #667eea; font-weight: 600;">Sen</td>
                <td style="padding: 0.5rem; color: #667eea; font-weight: 600;">Sel</td>
                <td style="padding: 0.5rem; color: #667eea; font-weight: 600;">Rab</td>
                <td style="padding: 0.5rem; color: #667eea; font-weight: 600;">Kam</td>
                <td style="padding: 0.5rem; color: #667eea; font-weight: 600;">Jum</td>
                <td style="padding: 0.5rem; color: #667eea; font-weight: 600;">Sab</td>
            </tr>
            @php
                $currentMonth = now();
                $firstDay = $currentMonth->copy()->startOfMonth();
                $startDay = $firstDay->dayOfWeek;
                $daysInMonth = $currentMonth->daysInMonth;
                $today = now()->day;
                $dayCounter = 1;
            @endphp
            @for ($week = 0; $week < 6; $week++)
                <tr>
                    @for ($day = 0; $day < 7; $day++)
                        @if ($week === 0 && $day < $startDay)
                            <td style="padding: 0.5rem; color: #d1d5db;"></td>
                        @elseif ($dayCounter > $daysInMonth)
                            <td style="padding: 0.5rem; color: #d1d5db;"></td>
                        @else
                            @php
                                $isToday = $dayCounter === $today;
                            @endphp
                            <td style="padding: 0.5rem; cursor: pointer; {{ $isToday ? 'background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 0.5rem; font-weight: 600;' : 'color: #1f2937;' }}">
                                {{ $dayCounter }}
                            </td>
                            @php $dayCounter++ @endphp
                        @endif
                    @endfor
                </tr>
                @if ($dayCounter > $daysInMonth)
                    @break
                @endif
            @endfor
        </table>
    </div>
    
    <!-- Jadwal Mendatang -->
    <div>
        <h3 style="font-size: 1rem; font-weight: 600; color: #1f2937; margin-bottom: 1rem;">
            <i class="fas fa-clock"></i> Jadwal Mendatang
        </h3>
        <div style="background: white; border-radius: 0.75rem; overflow: hidden; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05); border: 1px solid #e5e7eb;">
            @php
                $upcomingJadwals = \App\Models\Jadwal::where('tanggal', '>=', now()->toDateString())
                    ->orderBy('tanggal', 'asc')
                    ->limit(5)
                    ->get();
            @endphp
            @if($upcomingJadwals->count() > 0)
                @foreach($upcomingJadwals as $item)
                <div style="padding: 1rem; border-bottom: 1px solid #e5e7eb; display: flex; gap: 1rem;">
                    @php
                        $tanggal = $item->tanggal;
                        $bulan = $tanggal->translatedFormat('M');
                        $hari = $tanggal->day;
                    @endphp
                    <div style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white; border-radius: 0.5rem; padding: 0.5rem 1rem; min-width: 80px; text-align: center; display: flex; flex-direction: column; justify-content: center;">
                        <div style="font-size: 0.75rem; font-weight: 600;">{{ $bulan }}</div>
                        <div style="font-size: 1.25rem; font-weight: 700;">{{ $hari }}</div>
                    </div>
                    <div style="flex: 1;">
                        <div style="font-weight: 600; color: #1f2937; margin-bottom: 0.25rem;">{{ $item->nama_kegiatan }}</div>
                        <div style="font-size: 0.85rem; color: #6b7280;">{{ $item->jam_format }} | {{ $item->lokasi }}</div>
                    </div>
                </div>
                @endforeach
            @else
                <div style="padding: 2rem; text-align: center; color: #6b7280;">
                    <p>Tidak ada jadwal mendatang</p>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Filter -->
<div style="background: white; border-radius: 0.75rem; padding: 1.5rem; margin-bottom: 1.5rem; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05); border: 1px solid #e5e7eb;">
    <form method="GET" action="{{ route('jadwal') }}" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 1rem;">
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
                <input type="text" name="search" class="form-input" placeholder="Cari kegiatan, lokasi..." value="{{ request('search') }}" style="font-size: 0.9rem;">
                <button type="submit" class="btn btn-primary" style="padding: 0.75rem 1rem;">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </div>
    </form>
</div>

<!-- Daftar Jadwal Lengkap -->
<div style="background: white; border-radius: 0.75rem; overflow: hidden; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05); border: 1px solid #e5e7eb;">
    @if($jadwals->count() > 0)
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background-color: #f9fafb; border-bottom: 2px solid #e5e7eb;">
                    <th style="padding: 1rem; text-align: left; font-weight: 600; color: #374151; font-size: 0.85rem;">Kegiatan</th>
                    <th style="padding: 1rem; text-align: left; font-weight: 600; color: #374151; font-size: 0.85rem;">Tanggal & Waktu</th>
                    <th style="padding: 1rem; text-align: left; font-weight: 600; color: #374151; font-size: 0.85rem;">Lokasi</th>
                    <th style="padding: 1rem; text-align: left; font-weight: 600; color: #374151; font-size: 0.85rem;">PIC</th>
                    <th style="padding: 1rem; text-align: center; font-weight: 600; color: #374151; font-size: 0.85rem;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($jadwals as $jadwal)
                <tr style="border-bottom: 1px solid #e5e7eb; {{ $loop->odd ? '' : 'background-color: #f9fafb;' }}">
                    <td style="padding: 1rem; color: #1f2937; font-weight: 500;">{{ $jadwal->nama_kegiatan }}</td>
                    <td style="padding: 1rem; color: #6b7280;">{{ $jadwal->datetime_format }}</td>
                    <td style="padding: 1rem; color: #6b7280;">{{ $jadwal->lokasi }}</td>
                    <td style="padding: 1rem; color: #1f2937;">{{ $jadwal->pic }}</td>
                    <td style="padding: 1rem; text-align: center;">
                        <button type="button" class="btn" style="background: none; color: #667eea; padding: 0.25rem 0.5rem; border: none; cursor: pointer; font-size: 0.9rem;" onclick="viewJadwal({{ $jadwal->id }})">
                            <i class="fas fa-eye"></i>
                        </button>
                        @if(Auth::user()->role === 'admin')
                        <button type="button" class="btn" style="background: none; color: #667eea; padding: 0.25rem 0.5rem; border: none; cursor: pointer; font-size: 0.9rem;" onclick="editJadwal({{ $jadwal->id }})">
                            <i class="fas fa-edit"></i>
                        </button>
                        <form action="{{ route('jadwal.destroy', $jadwal) }}" method="POST" style="display: inline;" onsubmit="return confirm('Yakin ingin menghapus jadwal ini?');">
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
            {{ $jadwals->links() }}
        </div>
    @else
        <div style="padding: 3rem; text-align: center; color: #6b7280;">
            <i class="fas fa-calendar" style="font-size: 3rem; margin-bottom: 1rem; color: #d1d5db;"></i>
            <p>Belum ada data jadwal.</p>
        </div>
    @endif
</div>

@if(Auth::user()->role === 'admin')
<!-- Modal Tambah Jadwal -->
<div id="tambahJadwalModal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0, 0, 0, 0.5); z-index: 1000; align-items: center; justify-content: center;">
    <div style="background: white; border-radius: 1rem; padding: 2rem; max-width: 600px; width: 90%; max-height: 90vh; overflow-y: auto; box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; border-bottom: 1px solid #e5e7eb; padding-bottom: 1rem;">
            <h3 style="font-size: 1.25rem; font-weight: 600; color: #1f2937; margin: 0;">Tambah Jadwal Baru</h3>
            <button type="button" onclick="document.getElementById('tambahJadwalModal').style.display='none'" style="background: none; border: none; cursor: pointer; color: #6b7280; font-size: 1.5rem;">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <form action="{{ route('jadwal.store') }}" method="POST">
            @csrf
            
            <div class="form-group">
                <label class="form-label">Nama Kegiatan</label>
                <input type="text" name="nama_kegiatan" class="form-input @error('nama_kegiatan') border-red-500 @enderror" placeholder="Masukkan nama kegiatan..." required>
                @error('nama_kegiatan')
                    <small style="color: #ef4444;">{{ $message }}</small>
                @enderror
            </div>
            
            <div class="form-group">
                <label class="form-label">Tanggal</label>
                <input type="date" name="tanggal" class="form-input @error('tanggal') border-red-500 @enderror" required>
                @error('tanggal')
                    <small style="color: #ef4444;">{{ $message }}</small>
                @enderror
            </div>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div class="form-group">
                    <label class="form-label">Jam Mulai</label>
                    <input type="time" name="jam_mulai" class="form-input @error('jam_mulai') border-red-500 @enderror" required>
                    @error('jam_mulai')
                        <small style="color: #ef4444;">{{ $message }}</small>
                    @enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Jam Selesai</label>
                    <input type="time" name="jam_selesai" class="form-input @error('jam_selesai') border-red-500 @enderror" required>
                    @error('jam_selesai')
                        <small style="color: #ef4444;">{{ $message }}</small>
                    @enderror
                </div>
            </div>
            
            <div class="form-group">
                <label class="form-label">Lokasi</label>
                <input type="text" name="lokasi" class="form-input @error('lokasi') border-red-500 @enderror" placeholder="Masukkan lokasi kegiatan..." required>
                @error('lokasi')
                    <small style="color: #ef4444;">{{ $message }}</small>
                @enderror
            </div>
            
            <div class="form-group">
                <label class="form-label">PIC (Penanggung Jawab)</label>
                <input type="text" name="pic" class="form-input @error('pic') border-red-500 @enderror" placeholder="Masukkan nama PIC..." required>
                @error('pic')
                    <small style="color: #ef4444;">{{ $message }}</small>
                @enderror
            </div>
            
            <div class="form-group">
                <label class="form-label">Deskripsi</label>
                <textarea name="deskripsi" class="form-input @error('deskripsi') border-red-500 @enderror" rows="3" placeholder="Deskripsi kegiatan..."></textarea>
                @error('deskripsi')
                    <small style="color: #ef4444;">{{ $message }}</small>
                @enderror
            </div>
            
            <div style="display: flex; gap: 1rem; justify-content: flex-end;">
                <button type="button" class="btn btn-secondary" onclick="document.getElementById('tambahJadwalModal').style.display='none'">
                    Batal
                </button>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Simpan Jadwal
                </button>
            </div>
        </form>
    </div>
</div>
@endif

<!-- Modal Lihat Jadwal -->
<div id="lihatJadwalModal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0, 0, 0, 0.5); z-index: 1000; align-items: center; justify-content: center;">
    <div style="background: white; border-radius: 1rem; padding: 2rem; max-width: 600px; width: 90%; max-height: 90vh; overflow-y: auto; box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; border-bottom: 1px solid #e5e7eb; padding-bottom: 1rem;">
            <h3 style="font-size: 1.25rem; font-weight: 600; color: #1f2937; margin: 0;">Detail Jadwal</h3>
            <button type="button" onclick="closeLihatJadwalModal()" style="background: none; border: none; cursor: pointer; color: #6b7280; font-size: 1.5rem;">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <div id="lihatJadwalContent">
            <div style="text-align: center; padding: 2rem;">
                <i class="fas fa-spinner fa-spin" style="font-size: 2rem; color: #667eea;"></i>
                <p style="color: #6b7280; margin-top: 1rem;">Memuat data...</p>
            </div>
        </div>
    </div>
</div>

<!-- Modal Edit Jadwal -->
@if(Auth::user()->role === 'admin')
<div id="editJadwalModal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0, 0, 0, 0.5); z-index: 1000; align-items: center; justify-content: center;">
    <div style="background: white; border-radius: 1rem; padding: 2rem; max-width: 600px; width: 90%; max-height: 90vh; overflow-y: auto; box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; border-bottom: 1px solid #e5e7eb; padding-bottom: 1rem;">
            <h3 style="font-size: 1.25rem; font-weight: 600; color: #1f2937; margin: 0;">Edit Jadwal</h3>
            <button type="button" onclick="closeEditJadwalModal()" style="background: none; border: none; cursor: pointer; color: #6b7280; font-size: 1.5rem;">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <div id="editJadwalContent">
            <div style="text-align: center; padding: 2rem;">
                <i class="fas fa-spinner fa-spin" style="font-size: 2rem; color: #667eea;"></i>
                <p style="color: #6b7280; margin-top: 1rem;">Memuat data...</p>
            </div>
        </div>
    </div>
</div>
@endif

<script>
function viewJadwal(jadwalId) {
    document.getElementById('lihatJadwalModal').style.display = 'flex';
    
    fetch(`/jadwal/${jadwalId}/data`)
        .then(response => response.json())
        .then(data => {
            const content = `
                <div style="margin-bottom: 1rem;">
                    <div style="margin-bottom: 1rem;">
                        <strong style="color: #374151;">Nama Kegiatan:</strong>
                        <p style="color: #1f2937; margin: 0.25rem 0 0 0; font-size: 0.95rem;">${data.nama_kegiatan}</p>
                    </div>
                    <div style="margin-bottom: 1rem;">
                        <strong style="color: #374151;">Tanggal & Waktu:</strong>
                        <p style="color: #1f2937; margin: 0.25rem 0 0 0; font-size: 0.95rem;">${data.datetime_format}</p>
                    </div>
                    <div style="margin-bottom: 1rem;">
                        <strong style="color: #374151;">Lokasi:</strong>
                        <p style="color: #1f2937; margin: 0.25rem 0 0 0; font-size: 0.95rem;">${data.lokasi}</p>
                    </div>
                    <div style="margin-bottom: 1rem;">
                        <strong style="color: #374151;">PIC (Penanggung Jawab):</strong>
                        <p style="color: #1f2937; margin: 0.25rem 0 0 0; font-size: 0.95rem;">${data.pic}</p>
                    </div>
                    <div style="margin-bottom: 1rem;">
                        <strong style="color: #374151;">Deskripsi:</strong>
                        <p style="color: #1f2937; margin: 0.25rem 0 0 0; font-size: 0.95rem;">${data.deskripsi || '-'}</p>
                    </div>
                    <div style="color: #6b7280; font-size: 0.85rem; margin-top: 1rem; padding-top: 1rem; border-top: 1px solid #e5e7eb;">
                        <strong>Dibuat oleh:</strong> ${data.created_by} <br>
                        <strong>Tanggal dibuat:</strong> ${data.created_at}
                    </div>
                </div>
                <div style="display: flex; gap: 1rem; justify-content: flex-end; margin-top: 2rem;">
                    <button type="button" class="btn btn-secondary" onclick="closeLihatJadwalModal()">
                        Tutup
                    </button>
                </div>
            `;
            document.getElementById('lihatJadwalContent').innerHTML = content;
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('lihatJadwalContent').innerHTML = '<p style="color: #ef4444;">Gagal memuat data jadwal.</p>';
        });
}

function closeLihatJadwalModal() {
    const modal = document.getElementById('lihatJadwalModal');
    if (modal) modal.style.display = 'none';
}

function editJadwal(jadwalId) {
    document.getElementById('editJadwalModal').style.display = 'flex';
    
    fetch(`/jadwal/${jadwalId}/data`)
        .then(response => response.json())
        .then(data => {
            const content = `
                <form id="editJadwalForm" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="form-group">
                        <label class="form-label">Nama Kegiatan</label>
                        <input type="text" name="nama_kegiatan" class="form-input" value="${data.nama_kegiatan}" required>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Tanggal</label>
                        <input type="date" name="tanggal" class="form-input" value="${data.tanggal}" required>
                    </div>
                    
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                        <div class="form-group">
                            <label class="form-label">Jam Mulai</label>
                            <input type="time" name="jam_mulai" class="form-input" value="${data.jam_mulai}" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Jam Selesai</label>
                            <input type="time" name="jam_selesai" class="form-input" value="${data.jam_selesai}" required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Lokasi</label>
                        <input type="text" name="lokasi" class="form-input" value="${data.lokasi}" required>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">PIC (Penanggung Jawab)</label>
                        <input type="text" name="pic" class="form-input" value="${data.pic}" required>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="deskripsi" class="form-input" rows="3">${data.deskripsi || ''}</textarea>
                    </div>
                    
                    <div id="editErrorMessages" style="display: none; background-color: #fee2e2; border: 1px solid #fecaca; color: #991b1b; padding: 1rem; border-radius: 0.5rem; margin-bottom: 1rem;"></div>
                    
                    <div style="display: flex; gap: 1rem; justify-content: flex-end;">
                        <button type="button" class="btn btn-secondary" onclick="closeEditJadwalModal()">
                            Batal
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Simpan Perubahan
                        </button>
                    </div>
                </form>
            `;
            document.getElementById('editJadwalContent').innerHTML = content;
            
            document.getElementById('editJadwalForm').addEventListener('submit', function(e) {
                e.preventDefault();
                submitEditJadwal(jadwalId);
            });
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('editJadwalContent').innerHTML = '<p style="color: #ef4444;">Gagal memuat data jadwal.</p>';
        });
}

function closeEditJadwalModal() {
    const modal = document.getElementById('editJadwalModal');
    if (modal) modal.style.display = 'none';
}

function submitEditJadwal(jadwalId) {
    const form = document.getElementById('editJadwalForm');
    const formData = new FormData(form);
    
    fetch(`/jadwal/${jadwalId}`, {
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
        closeEditJadwalModal();
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
    const lihatModal = document.getElementById('lihatJadwalModal');
    const editModal = document.getElementById('editJadwalModal');
    const tambahModal = document.getElementById('tambahJadwalModal');
    
    if (e.target === lihatModal) {
        closeLihatJadwalModal();
    }
    if (e.target === editModal) {
        closeEditJadwalModal();
    }
    if (e.target === tambahModal) {
        tambahModal.style.display = 'none';
    }
});
</script>
@endsection

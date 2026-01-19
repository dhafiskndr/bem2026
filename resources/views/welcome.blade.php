@extends('layouts.app')

@section('title', 'Dashboard - BEM Management System')
@section('page-title', 'Dashboard')

@section('content')
<div style="margin-bottom: 2rem;">
    <h2 style="font-size: 1.5rem; font-weight: 600; color: #1f2937; margin-bottom: 0.5rem;">
        Selamat datang, {{ Auth::user()->name }}! 👋
    </h2>
    <p style="color: #6b7280;">Kelola dokumen dan jadwal kegiatan BEM dengan mudah</p>
</div>

<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; margin-bottom: 3rem;">
    <!-- Card Surat Masuk/Keluar -->
    <a href="{{ route('surat') }}" class="dashboard-card">
        <div class="dashboard-card-icon" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
            <i class="fas fa-envelope fa-lg"></i>
        </div>
        <div class="dashboard-card-title">Surat Masuk/Keluar</div>
        <div class="dashboard-card-desc">Kelola semua surat resmi organisasi</div>
        <div style="margin-top: 0.5rem; color: #667eea; font-weight: 500;">
            <span style="font-size: 2rem; display: block;">{{ $suratCount }}</span>
            <span style="font-size: 0.8rem;">{{ $suratMasuk }} Masuk | {{ $suratKeluar }} Keluar</span>
        </div>
    </a>
    
    <!-- Card Jadwal -->
    <a href="{{ route('jadwal') }}" class="dashboard-card">
        <div class="dashboard-card-icon" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
            <i class="fas fa-calendar-alt fa-lg"></i>
        </div>
        <div class="dashboard-card-title">Jadwal Kegiatan</div>
        <div class="dashboard-card-desc">Lihat dan atur jadwal acara BEM</div>
        <div style="margin-top: 0.5rem; color: #f5576c; font-weight: 500;">
            <span style="font-size: 2rem; display: block;">{{ $jadwalCount }}</span>
            <span style="font-size: 0.8rem;">{{ $jadwalMingguIni }} Minggu Ini</span>
        </div>
    </a>
    
    <!-- Card Anggaran -->
    <a href="{{ route('anggaran') }}" class="dashboard-card">
        <div class="dashboard-card-icon" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
            <i class="fas fa-chart-bar fa-lg"></i>
        </div>
        <div class="dashboard-card-title">Anggaran</div>
        <div class="dashboard-card-desc">Pantau pemasukan dan pengeluaran</div>
        <div style="margin-top: 0.5rem; color: #00f2fe; font-weight: 500;">
            <span style="font-size: 1.5rem; display: block; font-weight: 700;">Rp {{ number_format($saldoBersih, 0, ',', '.') }}</span>
            <span style="font-size: 0.8rem;">Saldo Bersih {{ $saldoBersih >= 0 ? '✅' : '❌' }}</span>
        </div>
    </a>
    
    <!-- Card RKT -->
    <a href="{{ route('rkt') }}" class="dashboard-card">
        <div class="dashboard-card-icon" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
            <i class="fas fa-tasks fa-lg"></i>
        </div>
        <div class="dashboard-card-title">RKT BEM</div>
        <div class="dashboard-card-desc">Rencana Kerja Tahunan organisasi</div>
        <div style="margin-top: 0.5rem; color: #38f9d7; font-weight: 500;">
            <span style="font-size: 2rem; display: block;">{{ $rktCount }}</span>
            <span style="font-size: 0.8rem;">{{ $rktSelesai }} Selesai | {{ $rktBerjalan }} Berjalan</span>
        </div>
    </a>
</div>

<!-- Quick Stats Section -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 1.5rem; margin-bottom: 3rem;">
    <!-- Statistik Surat -->
    <div style="background: white; border-radius: 0.75rem; padding: 1.5rem; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05); border: 1px solid #e5e7eb;">
        <h3 style="font-size: 1rem; font-weight: 600; color: #1f2937; margin-bottom: 1rem;">
            <i class="fas fa-envelope" style="color: #667eea;"></i> Statistik Surat
        </h3>
        <div style="display: grid; gap: 0.8rem;">
            <div style="display: flex; justify-content: space-between; align-items: center; padding: 0.75rem 0; border-bottom: 1px solid #e5e7eb;">
                <span style="color: #6b7280;">Total Surat</span>
                <span style="font-size: 1.25rem; font-weight: 700; color: #667eea;">{{ $suratCount }}</span>
            </div>
            <div style="display: flex; justify-content: space-between; align-items: center; padding: 0.75rem 0; border-bottom: 1px solid #e5e7eb;">
                <span style="color: #6b7280;">📥 Surat Masuk</span>
                <span style="font-size: 1.25rem; font-weight: 700; color: #43e97b;">{{ $suratMasuk }}</span>
            </div>
            <div style="display: flex; justify-content: space-between; align-items: center; padding: 0.75rem 0;">
                <span style="color: #6b7280;">📤 Surat Keluar</span>
                <span style="font-size: 1.25rem; font-weight: 700; color: #f5576c;">{{ $suratKeluar }}</span>
            </div>
        </div>
    </div>

    <!-- Statistik Jadwal -->
    <div style="background: white; border-radius: 0.75rem; padding: 1.5rem; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05); border: 1px solid #e5e7eb;">
        <h3 style="font-size: 1rem; font-weight: 600; color: #1f2937; margin-bottom: 1rem;">
            <i class="fas fa-calendar-alt" style="color: #f5576c;"></i> Statistik Jadwal
        </h3>
        <div style="display: grid; gap: 0.8rem;">
            <div style="display: flex; justify-content: space-between; align-items: center; padding: 0.75rem 0; border-bottom: 1px solid #e5e7eb;">
                <span style="color: #6b7280;">Total Jadwal</span>
                <span style="font-size: 1.25rem; font-weight: 700; color: #f5576c;">{{ $jadwalCount }}</span>
            </div>
            <div style="display: flex; justify-content: space-between; align-items: center; padding: 0.75rem 0; border-bottom: 1px solid #e5e7eb;">
                <span style="color: #6b7280;">📅 Minggu Ini</span>
                <span style="font-size: 1.25rem; font-weight: 700; color: #667eea;">{{ $jadwalMingguIni }}</span>
            </div>
            <div style="display: flex; justify-content: space-between; align-items: center; padding: 0.75rem 0;">
                <span style="color: #6b7280;">📝 Bulan Ini</span>
                <span style="font-size: 1.25rem; font-weight: 700; color: #f093fb;">
                    @php
                        $jadwalBulanIni = \App\Models\Jadwal::whereMonth('tanggal', now()->month)
                            ->whereYear('tanggal', now()->year)
                            ->count();
                    @endphp
                    {{ $jadwalBulanIni }}
                </span>
            </div>
        </div>
    </div>
</div>

<!-- Anggaran Detail -->
<div style="background: white; border-radius: 0.75rem; padding: 1.5rem; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05); border: 1px solid #e5e7eb; margin-bottom: 3rem;">
    <h3 style="font-size: 1.125rem; font-weight: 600; color: #1f2937; margin-bottom: 1.5rem;">
        <i class="fas fa-wallet" style="color: #00f2fe;"></i> Detail Keuangan
    </h3>
    
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem;">
        <!-- Pemasukan -->
        <div style="border-left: 4px solid #43e97b; padding-left: 1rem;">
            <div style="color: #6b7280; font-size: 0.9rem; margin-bottom: 0.5rem;">
                <i class="fas fa-arrow-down" style="color: #43e97b;"></i> Total Pemasukan
            </div>
            <div style="font-size: 1.5rem; font-weight: 700; color: #43e97b;">
                Rp {{ number_format($totalPemasukan, 0, ',', '.') }}
            </div>
        </div>
        
        <!-- Pengeluaran -->
        <div style="border-left: 4px solid #ef4444; padding-left: 1rem;">
            <div style="color: #6b7280; font-size: 0.9rem; margin-bottom: 0.5rem;">
                <i class="fas fa-arrow-up" style="color: #ef4444;"></i> Total Pengeluaran
            </div>
            <div style="font-size: 1.5rem; font-weight: 700; color: #ef4444;">
                Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}
            </div>
        </div>
        
        <!-- Saldo Bersih -->
        <div style="border-left: 4px solid {{ $saldoBersih >= 0 ? '#667eea' : '#ef4444' }}; padding-left: 1rem;">
            <div style="color: #6b7280; font-size: 0.9rem; margin-bottom: 0.5rem;">
                <i class="fas fa-wallet" style="color: {{ $saldoBersih >= 0 ? '#667eea' : '#ef4444' }};"></i> Saldo Bersih
            </div>
            <div style="font-size: 1.5rem; font-weight: 700; color: {{ $saldoBersih >= 0 ? '#667eea' : '#ef4444' }};">
                Rp {{ number_format($saldoBersih, 0, ',', '.') }}
            </div>
            <div style="font-size: 0.85rem; color: #6b7280; margin-top: 0.25rem;">
                {{ $saldoBersih >= 0 ? 'Surplus ✅' : 'Deficit ⚠️' }}
            </div>
        </div>
    </div>
</div>

<!-- RKT Progress -->
<div style="background: white; border-radius: 0.75rem; padding: 1.5rem; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05); border: 1px solid #e5e7eb; margin-bottom: 3rem;">
    <h3 style="font-size: 1.125rem; font-weight: 600; color: #1f2937; margin-bottom: 1.5rem;">
        <i class="fas fa-tasks" style="color: #38f9d7;"></i> Progress RKT
    </h3>
    
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 1.5rem; margin-bottom: 1.5rem;">
        <!-- Total Program -->
        <div style="border-left: 4px solid #667eea; padding-left: 1rem;">
            <div style="color: #6b7280; font-size: 0.9rem; margin-bottom: 0.5rem;">Total Program</div>
            <div style="font-size: 1.5rem; font-weight: 700; color: #667eea;">{{ $rktCount }}</div>
        </div>
        
        <!-- Belum Dimulai -->
        <div style="border-left: 4px solid #fbbf24; padding-left: 1rem;">
            <div style="color: #6b7280; font-size: 0.9rem; margin-bottom: 0.5rem;">Belum Dimulai</div>
            <div style="font-size: 1.5rem; font-weight: 700; color: #fbbf24;">{{ $rktBelum }}</div>
        </div>
        
        <!-- Sedang Berjalan -->
        <div style="border-left: 4px solid #667eea; padding-left: 1rem;">
            <div style="color: #6b7280; font-size: 0.9rem; margin-bottom: 0.5rem;">Sedang Berjalan</div>
            <div style="font-size: 1.5rem; font-weight: 700; color: #667eea;">{{ $rktBerjalan }}</div>
        </div>
        
        <!-- Selesai -->
        <div style="border-left: 4px solid #43e97b; padding-left: 1rem;">
            <div style="color: #6b7280; font-size: 0.9rem; margin-bottom: 0.5rem;">Selesai</div>
            <div style="font-size: 1.5rem; font-weight: 700; color: #43e97b;">{{ $rktSelesai }}</div>
        </div>
    </div>
    
    <!-- Progress Bar -->
    <div>
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
            <span style="color: #6b7280; font-weight: 500;">Persentase Program Selesai</span>
            <span style="font-weight: 700; color: #667eea;">{{ $programSelesai }}%</span>
        </div>
        <div style="background: #e5e7eb; border-radius: 9999px; height: 8px; overflow: hidden;">
            <div style="background: linear-gradient(90deg, #667eea 0%, #764ba2 100%); height: 100%; width: {{ $programSelesai }}%;"></div>
        </div>
    </div>
</div>

<!-- Info Box -->
<div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 0.75rem; padding: 2rem; color: white; text-align: center;">
    <i class="fas fa-lightbulb" style="font-size: 2rem; margin-bottom: 1rem; display: block;"></i>
    <h3 style="font-size: 1.125rem; font-weight: 600; margin-bottom: 0.5rem;">Semua data terintegrasi secara real-time</h3>
    <p style="margin: 0; opacity: 0.9;">Dashboard ini menampilkan data terbaru dari semua modul: Surat, Jadwal, Anggaran, dan RKT</p>
</div>
@endsection
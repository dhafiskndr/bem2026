<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Surat;
use App\Models\Jadwal;
use App\Models\Anggaran;
use App\Models\Rkt;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // Total Surat
        $suratCount = Surat::count();
        $suratMasuk = Surat::where('tipe', 'masuk')->count();
        $suratKeluar = Surat::where('tipe', 'keluar')->count();
        $suratMasukBulanIni = Surat::where('tipe', 'masuk')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        // Total Jadwal
        $jadwalCount = Jadwal::count();
        $jadwalMingguIni = Jadwal::whereBetween('tanggal', [
            now()->startOfWeek(),
            now()->endOfWeek()
        ])->count();

        // Anggaran
        $totalPemasukan = Anggaran::where('jenis', 'pemasukan')->sum('jumlah');
        $totalPengeluaran = Anggaran::where('jenis', 'pengeluaran')->sum('jumlah');
        $saldoBersih = $totalPemasukan - $totalPengeluaran;
        $statusAnggaran = $saldoBersih >= 0 ? 'Surplus' : 'Deficit';

        // RKT
        $rktCount = Rkt::count();
        $rktBerjalan = Rkt::where('status', 'berjalan')->count();
        $rktSelesai = Rkt::where('status', 'selesai')->count();
        $rktBelum = Rkt::where('status', 'belum')->count();
        
        // Hitung persentase program selesai
        $programSelesaiPercent = $rktCount > 0 ? round(($rktSelesai / $rktCount) * 100) : 0;

        // Data untuk dashboard
        $data = [
            'suratCount' => $suratCount,
            'suratMasuk' => $suratMasuk,
            'suratKeluar' => $suratKeluar,
            'suratMasukBulanIni' => $suratMasukBulanIni,
            'jadwalCount' => $jadwalCount,
            'jadwalMingguIni' => $jadwalMingguIni,
            'totalPemasukan' => $totalPemasukan,
            'totalPengeluaran' => $totalPengeluaran,
            'saldoBersih' => $saldoBersih,
            'rktCount' => $rktCount,
            'rktBerjalan' => $rktBerjalan,
            'rktSelesai' => $rktSelesai,
            'rktBelum' => $rktBelum,
            'statusAnggaran' => $statusAnggaran,
            'programSelesai' => $programSelesaiPercent,
        ];

        return view('dashboard', $data);
    }
}

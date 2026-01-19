<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Rkt extends Model
{
    protected $table = 'rkts';
    
    protected $fillable = [
        'nama_program',
        'divisi',
        'deskripsi',
        'tujuan',
        'target_peserta',
        'lokasi',
        'tanggal_mulai',
        'tanggal_selesai',
        'pic',
        'anggaran',
        'status',
        'created_by',
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
    ];

    public function getTanggalMulaiFormatAttribute()
    {
        return $this->tanggal_mulai->translatedFormat('d F Y');
    }

    public function getTanggalSelesaiFormatAttribute()
    {
        return $this->tanggal_selesai->translatedFormat('d F Y');
    }

    public function getStatusLabelAttribute()
    {
        return match($this->status) {
            'belum' => 'Belum Dimulai',
            'berjalan' => 'Sedang Berjalan',
            'selesai' => 'Selesai',
            default => $this->status,
        };
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'belum' => '#fbbf24',
            'berjalan' => '#667eea',
            'selesai' => '#43e97b',
            default => '#6b7280',
        };
    }

    public function getStatusBgColorAttribute()
    {
        return match($this->status) {
            'belum' => '#fef3c7',
            'berjalan' => '#eef2ff',
            'selesai' => '#d1fae5',
            default => '#f3f4f6',
        };
    }

    public function getDivisilabelAttribute()
    {
        return match($this->divisi) {
            'agama' => 'Dept. Agama',
            'dageri' => 'Dept. Dageri',
            'minba' => 'Dept. Minba',
            'sospen' => 'Dept. Sospen',
            'kominfo' => 'Dept. Kominfo',
            default => $this->divisi,
        };
    }

    public function getDivisiColorAttribute()
    {
        return match($this->divisi) {
            'agama' => '#667eea',
            'dageri' => '#f093fb',
            'minba' => '#43e97b',
            'sospen' => '#f5576c',
            'kominfo' => '#4facfe',
            default => '#6b7280',
        };
    }

    public function getAnggaranFormatAttribute()
    {
        return 'Rp ' . number_format($this->anggaran, 0, ',', '.');
    }

    public function getDurasiAttribute()
    {
        return $this->tanggal_mulai->diffInDays($this->tanggal_selesai) + 1;
    }

    public function getProgressPercentAttribute()
    {
        $today = now()->toDateString();
        $mulai = $this->tanggal_mulai->toDateString();
        $selesai = $this->tanggal_selesai->toDateString();

        if ($today < $mulai) {
            return 0;
        } elseif ($today > $selesai) {
            return 100;
        } else {
            $total = $this->tanggal_mulai->diffInDays($this->tanggal_selesai) + 1;
            $elapsed = $this->tanggal_mulai->diffInDays($today) + 1;
            return round(($elapsed / $total) * 100);
        }
    }
}

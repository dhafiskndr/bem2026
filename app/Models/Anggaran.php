<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Anggaran extends Model
{
    use HasFactory;

    protected $table = 'anggaran';

    protected $fillable = [
        'tanggal',
        'jenis',
        'keterangan',
        'jumlah',
        'pic',
        'catatan',
        'created_by',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    /**
     * Get formatted tanggal (d F Y)
     */
    public function getTanggalFormatAttribute()
    {
        return $this->tanggal->translatedFormat('d F Y');
    }

    /**
     * Get jenis label (Pemasukan/Pengeluaran)
     */
    public function getJenisLabelAttribute()
    {
        return $this->jenis === 'pemasukan' ? 'Pemasukan' : 'Pengeluaran';
    }

    /**
     * Get jenis color (for badges)
     */
    public function getJenisColorAttribute()
    {
        if ($this->jenis === 'pemasukan') {
            return 'bg-green-100 text-green-800';
        }
        return 'bg-red-100 text-red-800';
    }

    /**
     * Get formatted jumlah (with currency symbol)
     */
    public function getJumlahFormatAttribute()
    {
        $prefix = $this->jenis === 'pemasukan' ? '+ ' : '- ';
        return $prefix . 'Rp ' . number_format($this->jumlah, 0, ',', '.');
    }

    /**
     * Get color for jumlah display
     */
    public function getJumlahColorAttribute()
    {
        return $this->jenis === 'pemasukan' ? 'text-green-600' : 'text-red-600';
    }
}

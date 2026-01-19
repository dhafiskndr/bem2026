<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Surat extends Model
{
    use HasFactory;

    protected $fillable = [
        'nomor_surat',
        'tipe',
        'perihal',
        'tanggal_surat',
        'file_path',
        'dari_tujuan',
        'keterangan',
        'created_by',
    ];

    protected $casts = [
        'tanggal_surat' => 'date',
    ];

    /**
     * Format tanggal untuk display
     */
    public function getTanggalFormatAttribute()
    {
        return $this->tanggal_surat->format('d F Y');
    }

    /**
     * Get tipe label
     */
    public function getTipeLabelAttribute()
    {
        return $this->tipe === 'masuk' ? 'Surat Masuk' : 'Surat Keluar';
    }

    /**
     * Get tipe color badge
     */
    public function getTipeColorAttribute()
    {
        return $this->tipe === 'masuk' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800';
    }
}


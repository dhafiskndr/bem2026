<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jadwal extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_kegiatan',
        'tanggal',
        'jam_mulai',
        'jam_selesai',
        'lokasi',
        'pic',
        'deskripsi',
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
     * Get formatted jam (H:i)
     */
    public function getJamFormatAttribute()
    {
        return $this->jam_mulai . ' - ' . $this->jam_selesai;
    }

    /**
     * Get full datetime info
     */
    public function getDatetimeFormatAttribute()
    {
        return $this->tanggal_format . ', ' . $this->jam_format;
    }
}

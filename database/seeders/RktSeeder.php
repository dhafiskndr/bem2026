<?php

namespace Database\Seeders;

use App\Models\Rkt;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class RktSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rktData = [
            [
                'nama_program' => 'Orientasi Anggota Baru',
                'divisi' => 'agama',
                'deskripsi' => 'Program orientasi untuk anggota BEM baru',
                'tujuan' => 'Memahami visi misi BEM dan mengenal antar anggota',
                'target_peserta' => 50,
                'lokasi' => 'Aula Utama',
                'tanggal_mulai' => '2026-01-10',
                'tanggal_selesai' => '2026-02-15',
                'pic' => 'Budi Santoso',
                'anggaran' => 5000000,
                'status' => 'berjalan',
                'created_by' => 'Admin',
            ],
            [
                'nama_program' => 'Seminar Kepemimpinan',
                'divisi' => 'kominfo',
                'deskripsi' => 'Seminar tentang kepemimpinan dan manajemen organisasi',
                'tujuan' => 'Meningkatkan kualitas kepemimpinan anggota BEM',
                'target_peserta' => 200,
                'lokasi' => 'Gedung Student Center',
                'tanggal_mulai' => '2026-03-01',
                'tanggal_selesai' => '2026-03-10',
                'pic' => 'Siti Nurhaliza',
                'anggaran' => 8000000,
                'status' => 'belum',
                'created_by' => 'Admin',
            ],
            [
                'nama_program' => 'Bakti Sosial Komunitas',
                'divisi' => 'sospen',
                'deskripsi' => 'Program bakti sosial membantu masyarakat sekitar kampus',
                'tujuan' => 'Meningkatkan kepedulian sosial mahasiswa',
                'target_peserta' => 100,
                'lokasi' => 'Desa Terpilih',
                'tanggal_mulai' => '2026-04-15',
                'tanggal_selesai' => '2026-04-30',
                'pic' => 'Ahmad Rizki',
                'anggaran' => 12000000,
                'status' => 'belum',
                'created_by' => 'Admin',
            ],
            [
                'nama_program' => 'Pelatihan Dasar Kepemimpinan',
                'divisi' => 'dageri',
                'deskripsi' => 'Pelatihan dasar kepemimpinan untuk pengurus baru',
                'tujuan' => 'Mempersiapkan pengurus yang berkualitas',
                'target_peserta' => 30,
                'lokasi' => 'Rumah Singgah',
                'tanggal_mulai' => '2026-02-01',
                'tanggal_selesai' => '2026-02-28',
                'pic' => 'Dewi Lestari',
                'anggaran' => 7000000,
                'status' => 'berjalan',
                'created_by' => 'Admin',
            ],
            [
                'nama_program' => 'Festival Seni dan Budaya',
                'divisi' => 'minba',
                'deskripsi' => 'Festival untuk menampilkan seni dan budaya mahasiswa',
                'tujuan' => 'Melestarikan budaya dan seni lokal',
                'target_peserta' => 500,
                'lokasi' => 'Lapangan Universitas',
                'tanggal_mulai' => '2026-05-10',
                'tanggal_selesai' => '2026-05-20',
                'pic' => 'Rina Wijaya',
                'anggaran' => 20000000,
                'status' => 'belum',
                'created_by' => 'Admin',
            ],
            [
                'nama_program' => 'Workshop Teknologi dan Inovasi',
                'divisi' => 'kominfo',
                'deskripsi' => 'Workshop tentang teknologi terkini dan inovasi',
                'tujuan' => 'Meningkatkan skill teknologi mahasiswa',
                'target_peserta' => 150,
                'lokasi' => 'Lab Komputer',
                'tanggal_mulai' => '2026-06-01',
                'tanggal_selesai' => '2026-06-15',
                'pic' => 'Hendra Gunawan',
                'anggaran' => 10000000,
                'status' => 'belum',
                'created_by' => 'Admin',
            ],
        ];

        foreach ($rktData as $data) {
            Rkt::create($data);
        }
    }
}

<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Surat;
use App\Models\Jadwal;
use App\Models\Anggaran;
use App\Models\Rkt;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create test user
        $user = User::firstOrCreate(
            ['email' => 'admin@bem.com'],
            [
                'name' => 'Admin BEM',
                'password' => bcrypt('password'),
                'role' => 'admin',
                'tanggal_lahir' => now()->subYears(25),
            ]
        );

        // Create test surat data
        Surat::firstOrCreate(
            ['nomor_surat' => '001/SK/2026'],
            [
                'tipe' => 'keluar',
                'perihal' => 'Pengumuman Kepengurusan BEM 2026',
                'tanggal_surat' => now()->subDays(5),
                'dari_tujuan' => 'Sekretariat BEM',
                'keterangan' => 'Surat pemberitahuan tentang struktur kepengurusan BEM 2026',
                'created_by' => $user->name,
            ]
        );

        Surat::firstOrCreate(
            ['nomor_surat' => '002/AS/2026'],
            [
                'tipe' => 'masuk',
                'perihal' => 'Permohonan Sponsorship Event Akademik',
                'tanggal_surat' => now()->subDays(3),
                'dari_tujuan' => 'Himpunan Mahasiswa Informatika',
                'keterangan' => 'Permohonan dukungan dana untuk acara seminar teknologi',
                'created_by' => $user->name,
            ]
        );

        // Create test jadwal data
        Jadwal::firstOrCreate(
            ['nama_kegiatan' => 'Rapat Koordinasi Pengurus BEM'],
            [
                'tanggal' => now()->addDays(1),
                'jam_mulai' => '10:00',
                'jam_selesai' => '12:00',
                'lokasi' => 'Ruang Rapat Gedung A',
                'pic' => 'Ketua BEM',
                'deskripsi' => 'Rapat koordinasi pembahasan program kerja dan evaluasi kegiatan bulan ini',
                'created_by' => $user->name,
            ]
        );

        Jadwal::firstOrCreate(
            ['nama_kegiatan' => 'Seminar Kepemimpinan'],
            [
                'tanggal' => now()->addDays(5),
                'jam_mulai' => '14:00',
                'jam_selesai' => '17:00',
                'lokasi' => 'Auditorium Utama',
                'pic' => 'Divisi Pendidikan',
                'deskripsi' => 'Seminar mengenai pengembangan skill kepemimpinan untuk semua anggota BEM',
                'created_by' => $user->name,
            ]
        );

        Jadwal::firstOrCreate(
            ['nama_kegiatan' => 'Acara Sosialisasi Program Tahunan'],
            [
                'tanggal' => now()->addDays(10),
                'jam_mulai' => '13:00',
                'jam_selesai' => '15:30',
                'lokasi' => 'Lapangan Utama Kampus',
                'pic' => 'Divisi Hubungan Eksternal',
                'deskripsi' => 'Sosialisasi program kerja tahunan BEM 2026 kepada semua mahasiswa',
                'created_by' => $user->name,
            ]
        );

        // Create test anggaran data
        Anggaran::firstOrCreate(
            ['keterangan' => 'Iuran Anggota Bulan Januari'],
            [
                'tanggal' => now()->subDays(10),
                'jenis' => 'pemasukan',
                'jumlah' => 5000000,
                'pic' => 'Bendahara',
                'catatan' => 'Iuran anggota BEM dari 50 anggota aktif',
                'created_by' => $user->name,
            ]
        );

        Anggaran::firstOrCreate(
            ['keterangan' => 'Pembelian Perlengkapan Acara'],
            [
                'tanggal' => now()->subDays(7),
                'jenis' => 'pengeluaran',
                'jumlah' => 2000000,
                'pic' => 'Ketua Divisi Acara',
                'catatan' => 'Pembelian spanduk, mic, dan sound system untuk acara sosialisasi',
                'created_by' => $user->name,
            ]
        );

        Anggaran::firstOrCreate(
            ['keterangan' => 'Penyewaan Ruang Rapat'],
            [
                'tanggal' => now()->subDays(5),
                'jenis' => 'pengeluaran',
                'jumlah' => 500000,
                'pic' => 'Divisi Administrasi',
                'catatan' => 'Penyewaan ruang rapat untuk koordinasi pengurus BEM',
                'created_by' => $user->name,
            ]
        );

        Anggaran::firstOrCreate(
            ['keterangan' => 'Donasi dari Alumni'],
            [
                'tanggal' => now()->subDays(2),
                'jenis' => 'pemasukan',
                'jumlah' => 3000000,
                'pic' => 'Ketua BEM',
                'catatan' => 'Donasi dari alumni untuk kegiatan sosial BEM tahun 2026',
                'created_by' => $user->name,
            ]
        );

        // Call RKT Seeder
        $this->call(RktSeeder::class);
    }
}

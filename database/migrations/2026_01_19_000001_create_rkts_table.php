<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('rkts', function (Blueprint $table) {
            $table->id();
            $table->string('nama_program');
            $table->enum('divisi', ['agama', 'dageri', 'minba', 'sospen', 'kominfo']);
            $table->longText('deskripsi')->nullable();
            $table->longText('tujuan')->nullable();
            $table->integer('target_peserta')->nullable();
            $table->string('lokasi')->nullable();
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->string('pic');
            $table->decimal('anggaran', 15, 0)->default(0);
            $table->enum('status', ['belum', 'berjalan', 'selesai'])->default('belum');
            $table->string('created_by');
            $table->timestamps();

            $table->index('divisi');
            $table->index('status');
            $table->index('tanggal_mulai');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rkts');
    }
};

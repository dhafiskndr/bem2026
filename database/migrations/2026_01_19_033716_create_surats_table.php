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
        Schema::create('surats', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_surat')->unique();
            $table->enum('tipe', ['masuk', 'keluar'])->comment('Tipe surat: masuk atau keluar');
            $table->text('perihal');
            $table->date('tanggal_surat');
            $table->string('file_path')->nullable()->comment('Path file surat');
            $table->string('dari_tujuan')->nullable()->comment('Asal/tujuan surat');
            $table->text('keterangan')->nullable();
            $table->string('created_by')->comment('User yang membuat record');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surats');
    }
};

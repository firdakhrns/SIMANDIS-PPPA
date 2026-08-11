<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('agendas', function (Blueprint $table) {
            $table->id();
            $table->string('no_surat');
            $table->dateTime('tgl_surat');
            $table->date('tgl_kegiatan')->nullable(); 
            $table->date('tgl_diterima');
            $table->string('no_agenda');
            $table->enum('sifat_surat', ['Sangat Segera', 'Segera', 'Rahasia']);
            $table->string('surat_dari');
            $table->text('perihal');
            $table->string('file_pdf')->nullable();
            $table->string('file_surat')->nullable(); 
            $table->enum('status_pelaksanaan', ['belum', 'terlaksana'])->default('belum');
            
            $table->integer('bidang_id'); 
            
            $table->enum('status_disposisi', ['Hadir', 'Disposisi'])->nullable()->default(null);
            $table->text('diteruskan_kepada')->nullable(); 
            $table->text('instruksi_pimpinan')->nullable(); 
            $table->text('catatan_kadis')->nullable(); 
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('agendas');
    }
};
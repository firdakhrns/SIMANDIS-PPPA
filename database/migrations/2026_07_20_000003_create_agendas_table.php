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
            
            $table->foreignId('surat_id')->constrained('surats')->onDelete('cascade');
            
            $table->string('no_agenda');
            $table->integer('bidang_id'); 
            $table->date('tgl_kegiatan');
            $table->time('jam_kegiatan')->default('08:30:00');
            $table->string('lokasi')->nullable();
            $table->enum('status_pelaksanaan', ['belum', 'terlaksana'])->default('belum');
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('agendas');
    }
};
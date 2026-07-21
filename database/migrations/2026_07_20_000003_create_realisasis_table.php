<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('realisasis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('agenda_id')
                  ->constrained('agendas')
                  ->onDelete('cascade'); 
            
            $table->integer('jumlah_peserta')->default(0);
            
            $table->string('file_surat_tugas');
            $table->text('foto_dokumentasi'); 
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('realisasis');
    }
};
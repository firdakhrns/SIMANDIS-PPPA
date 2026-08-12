<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('surats', function (Blueprint $table) {
            $table->id();
            $table->string('no_surat');
            $table->dateTime('tgl_surat');
            $table->date('tgl_diterima');
            $table->enum('sifat_surat', ['Segera', 'Sangat Segera', 'Rahasia'])->default('Segera');
            $table->string('surat_dari');
            $table->text('perihal');
            $table->string('file_pdf')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('surats');
    }
};
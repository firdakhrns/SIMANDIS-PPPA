<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('targets', function (Blueprint $table) {
            $table->id();
            $table->integer('bidang_id'); 
            $table->year('tahun');
            $table->integer('jumlah_target');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('targets');
    }
};
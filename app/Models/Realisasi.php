<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Realisasi extends Model
{
    protected $fillable = [
        'agenda_id', 'jumlah_peserta', 'file_surat_tugas', 'foto_dokumentasi'
    ];

    protected $casts = [
        'foto_dokumentasi' => 'array', 
    ];

    public function agenda(): BelongsTo
    {
        return $this->belongsTo(Agenda::class);
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Agenda extends Model
{
    protected $fillable = [
        'no_surat', 'tgl_surat', 'tgl_diterima', 'no_agenda', 'sifat_surat',
        'surat_dari', 'perihal', 'file_pdf', 'status_pelaksanaan','bidang_id', 
        'status_disposisi', 'diteruskan_kepada', 'instruksi_pimpinan', 'catatan_kadis'
    ];

    protected $casts = [
        'diteruskan_kepada' => 'array',
        'instruksi_pimpinan' => 'array',
    ];

    public function realisasi(): HasOne
    {
        return $this->hasOne(Realisasi::class);
    }
}
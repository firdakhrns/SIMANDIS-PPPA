<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agenda extends Model
{
    use HasFactory;

    protected $fillable = [
        'surat_id',
        'no_agenda',
        'bidang_id',
        'tgl_kegiatan',
        'jam_kegiatan',
        'lokasi', 
        'status_pelaksanaan',
    ];

    public function surat()
    {
        return $this->belongsTo(Surat::class);
    }

    public function disposisi()
    {
        return $this->hasOne(Disposisi::class);
    }
}
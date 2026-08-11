<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Disposisi;

class Agenda extends Model
{
    use HasFactory;

    protected $fillable = [
        'surat_id', 'no_agenda', 'bidang_id', 
        'tgl_kegiatan', 'jam_kegiatan', 'status_pelaksanaan'
    ];

    public function surat()
    {
        return $this->belongsTo(Surat::class, 'surat_id');
    }

    public function disposisi()
    {
        return $this->hasOne(Disposisi::class, 'agenda_id');
    }
}
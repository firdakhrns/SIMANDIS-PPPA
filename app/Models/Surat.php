<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Surat extends Model
{
    use HasFactory;

    protected $fillable = [
        'no_surat', 'tgl_surat', 'tgl_diterima', 
        'sifat_surat', 'surat_dari', 'perihal', 'file_pdf'
    ];

    public function agenda()
    {
        return $this->hasOne(Agenda::class, 'surat_id');
    }
}
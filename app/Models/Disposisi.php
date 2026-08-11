<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Disposisi extends Model
{
    use HasFactory;

    protected $fillable = [
        'agenda_id', 
        'status_disposisi', 
        'diteruskan_kepada', 
        'catatan_kadis'
    ];

    protected $attributes = [
        'status_disposisi' => null,
        'diteruskan_kepada' => null,
        'catatan_kadis' => null,
    ];

    public function agenda()
    {
        return $this->belongsTo(Agenda::class, 'agenda_id');
    }
}
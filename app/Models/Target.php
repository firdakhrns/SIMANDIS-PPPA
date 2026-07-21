<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Target extends Model
{
    protected $fillable = ['bidang_id', 'tahun', 'jumlah_target'];
}
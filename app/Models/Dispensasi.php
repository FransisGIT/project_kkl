<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dispensasi extends Model
{
    protected $fillable = [
        'user_id',
        'tahun_akademik',
        'jumlah_pengajuan',
        'no_hp',
        'tanggal_deadline',
        'file_surat',
        'status',
    ];
}

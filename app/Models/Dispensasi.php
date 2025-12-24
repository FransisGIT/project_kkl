<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dispensasi extends Model
{
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'id_user',
        'tahun_akademik',
        'jumlah_pengajuan',
        'no_hp',
        'tanggal_deadline',
        'file_surat',
        'file_pdf',
        'payment_proof',
        'status',
        'approver_notes',
    ];

    protected $casts = [
        'approver_notes' => 'array',
    ];
}

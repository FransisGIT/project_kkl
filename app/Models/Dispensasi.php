<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

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
        'surat_dispensasi',
        'payment_proof',
        'status',
        'approver_notes',
    ];

    protected $casts = [
        'approver_notes' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }
}

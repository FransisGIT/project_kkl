<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RencanaStudi extends Model
{
    use HasFactory;

    protected $table = 'rencana_studi';

    /**
     * The primary key associated with the table.
     */
    protected $primaryKey = 'id_rencana_studi';

    protected $fillable = [
        'id_user',
        'id_mata_kuliah',
        'status',
        'catatan',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'id_mata_kuliah' => 'array',
    ];

    /**
     * Get the user that owns the rencana studi.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    /**
     * Get mata kuliah collection from JSON IDs
     */
    public function getMataKuliahAttribute()
    {
        if (empty($this->id_mata_kuliah)) {
            return collect([]);
        }
        return MataKuliah::whereIn('id_matakuliah', $this->id_mata_kuliah)->get();
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MataKuliah extends Model
{
    use HasFactory;

    protected $table = 'mata_kuliah';

    /**
     * The primary key associated with the table.
     */
    protected $primaryKey = 'id_matakuliah';

    /**
     * The data type of the primary key.
     */
    protected $keyType = 'string';

    /**
     * Indicates if the IDs are auto-incrementing.
     */
    public $incrementing = false;

    protected $fillable = [
        'kode_matakuliah',
        'nama_matakuliah',
        'sks',
        'semester',
        'group',
        'hari',
        'jam',
        'kapasitas',
        'peserta',
    ];

    /**
     * Get users for the mata kuliah (many-to-many through rencana_studi).
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'rencana_studi', 'id_matakuliah', 'id_user')
                    ->withTimestamps();
    }

    /**
     * Get rencana studi for the mata kuliah.
     */
    public function rencanaStudi()
    {
        return $this->hasMany(RencanaStudi::class, 'id_matakuliah', 'id_matakuliah');
    }
}

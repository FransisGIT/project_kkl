<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MataKuliah extends Model
{
    use HasFactory;

    protected $table = 'mata_kuliah';


    protected $primaryKey = 'id_matakuliah';


    protected $keyType = 'string';


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
        'prasyarat_ids',
    ];


    protected $casts = [
        'prasyarat_ids' => 'array',
    ];


    public function users()
    {
        return $this->belongsToMany(User::class, 'rencana_studi', 'id_matakuliah', 'id_user')
            ->withTimestamps();
    }


    public function rencanaStudi()
    {
        return $this->hasMany(RencanaStudi::class, 'id_matakuliah', 'id_matakuliah');
    }


    public function prasyarat()
    {
        if (empty($this->prasyarat_ids)) {
            return collect([]);
        }
        return MataKuliah::whereIn('id_matakuliah', $this->prasyarat_ids)->get();
    }


    public function nilaiMahasiswa()
    {
        return $this->hasMany(\App\Models\NilaiMahasiswa::class, 'id_matakuliah', 'id_matakuliah');
    }
}

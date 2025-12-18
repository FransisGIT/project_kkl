<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NilaiMahasiswa extends Model
{
    use HasFactory;

    protected $table = 'nilai_mahasiswa';
    protected $primaryKey = 'id_nilai';

    protected $fillable = [
        'id_user',
        'id_matakuliah',
        'nilai',
        'nilai_angka',
        'status',
        'semester_ambil',
    ];

    /**
     * Get the user (mahasiswa)
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    /**
     * Get the mata kuliah
     */
    public function mataKuliah()
    {
        return $this->belongsTo(MataKuliah::class, 'id_matakuliah', 'id_matakuliah');
    }
}

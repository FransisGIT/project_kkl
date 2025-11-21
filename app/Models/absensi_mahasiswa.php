<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class absensi_mahasiswa extends Model
{
    use HasFactory;
    protected $fillable = ['nim','nama','jam_absen','tanggal_absen','mata_kuliah','ruangan'];
    public $timestamps = false;
}

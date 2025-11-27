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
        'id_matakuliah',
    ];

    /**
     * Get the user that owns the rencana studi.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    /**
     * Get the mata kuliah for the rencana studi.
     */
    public function mataKuliah()
    {
        return $this->belongsTo(MataKuliah::class, 'id_matakuliah', 'id_matakuliah');
    }
}

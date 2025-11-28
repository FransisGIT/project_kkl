<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The primary key associated with the table.
     */
    protected $primaryKey = 'id_user';

    /**
     * The data type of the primary key.
     */
    protected $keyType = 'string';

    /**
     * Indicates if the IDs are auto-incrementing.
     */
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'id_role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        // 'email_verified_at' => 'datetime',
    ];

    /**
     * Get the role that owns the user.
     */
    public function role()
    {
        return $this->belongsTo(Role::class, 'id_role', 'id_role');
    }

// ...existing code...

    /**
     * Many-to-many: user <-> mata_kuliah melalui tabel rencana_studi
     */
    public function mataKuliah()
    {
        // asumsi kolom pivot: id_user, id_matakuliah
        return $this->belongsToMany(\App\Models\MataKuliah::class, 'rencana_studi', 'id_user', 'id_matakuliah')
            ->withTimestamps();
    }

    /**
     * One-to-many: user -> rencana_studi
     */
    public function rencanaStudi()
    {
        // asumsikan primary key User = 'id'; ganti jika beda (mis. 'id_user')
        return $this->hasMany(\App\Models\RencanaStudi::class, 'id_user', 'id_user');
    }

    // ...existing code...
}

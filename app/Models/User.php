<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;


    protected $primaryKey = 'id_user';


    protected $keyType = 'string';


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
        'tunggakan',
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


    public function role()
    {
        return $this->belongsTo(Role::class, 'id_role', 'id_role');
    }

    /**
     * One-to-many: user -> rencana_studi
     */
    public function rencanaStudi()
    {
        return $this->hasMany(\App\Models\RencanaStudi::class, 'id_user', 'id_user');
    }


    public function rencanaStudiAktif()
    {
        return $this->hasOne(\App\Models\RencanaStudi::class, 'id_user', 'id_user')
            ->latest();
    }


    public function getMataKuliahDiambilAttribute()
    {
        $rencanaAktif = $this->rencanaStudiAktif;
        if (!$rencanaAktif || empty($rencanaAktif->id_mata_kuliah)) {
            return [];
        }
        return $rencanaAktif->id_mata_kuliah;
    }


    public function nilaiMahasiswa()
    {
        return $this->hasMany(\App\Models\NilaiMahasiswa::class, 'id_user', 'id_user');
    }
}

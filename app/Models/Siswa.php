<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    protected $table = 'siswa';
    protected $primaryKey = 'id_siswa';
    public $timestamps = false;

    protected $fillable = [
        'id_user',
        'nis',
        'id_kelas',
        'nama_orangtua',
        'no_hp_orangtua',
        'alamat',
        'tanggal_lahir',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'created_at' => 'datetime',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'id_kelas', 'id_kelas');
    }

    public function presensiSiswa()
    {
        return $this->hasMany(PresensiSiswa::class, 'id_siswa', 'id_siswa');
    }

    public function pengumpulanTugas()
    {
        return $this->hasMany(PengumpulanTugas::class, 'id_siswa', 'id_siswa');
    }
}

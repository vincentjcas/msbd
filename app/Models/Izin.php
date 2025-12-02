<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Izin extends Model
{
    protected $table = 'izin';
    protected $primaryKey = 'id_izin';
    public $timestamps = false;

    protected $fillable = [
        'id_user',
        'id_guru',
        'id_jadwal',
        'tanggal',
        'alasan',
        'bukti_file',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'created_at' => 'datetime',
        'approved_at' => 'datetime',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    public function guru()
    {
        return $this->belongsTo(Guru::class, 'id_guru', 'id_guru');
    }

    public function jadwal()
    {
        return $this->belongsTo(Jadwal::class, 'id_jadwal', 'id_jadwal');
    }

    // (Relasi dan scope status dihapus karena sudah tidak dipakai)
}

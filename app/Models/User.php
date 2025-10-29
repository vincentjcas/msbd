<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    protected $table = 'users';
    protected $primaryKey = 'id_user';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'username',
        'password',
        'role',
        'nama_lengkap',
        'email',
        'no_hp',
        'status_aktif',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'status_aktif' => 'boolean',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    // Relationships
    public function guru()
    {
        return $this->hasOne(Guru::class, 'id_user', 'id_user');
    }

    public function siswa()
    {
        return $this->hasOne(Siswa::class, 'id_user', 'id_user');
    }

    public function kepalaSekolah()
    {
        return $this->hasOne(KepalaSekolah::class, 'id_user', 'id_user');
    }

    public function pembina()
    {
        return $this->hasOne(Pembina::class, 'id_user', 'id_user');
    }

    public function presensi()
    {
        return $this->hasMany(Presensi::class, 'id_user', 'id_user');
    }

    public function izin()
    {
        return $this->hasMany(Izin::class, 'id_user', 'id_user');
    }

    public function logAktivitas()
    {
        return $this->hasMany(LogAktivitas::class, 'id_user', 'id_user');
    }

    public function backupLog()
    {
        return $this->hasMany(BackupLog::class, 'dibuat_oleh', 'id_user');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status_aktif', 1);
    }

    public function scopeByRole($query, $role)
    {
        return $query->where('role', $role);
    }

    public function scopeGuru($query)
    {
        return $query->where('role', 'guru');
    }

    public function scopeSiswa($query)
    {
        return $query->where('role', 'siswa');
    }

    public function scopeKepalaSekolah($query)
    {
        return $query->where('role', 'kepala_sekolah');
    }

    public function scopePembina($query)
    {
        return $query->where('role', 'pembina');
    }

    public function scopeAdmin($query)
    {
        return $query->where('role', 'admin');
    }
}


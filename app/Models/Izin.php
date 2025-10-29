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
        'tanggal',
        'alasan',
        'bukti_file',
        'status_approval',
        'disetujui_oleh',
        'catatan_approval',
        'approved_at',
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

    public function approver()
    {
        return $this->belongsTo(User::class, 'disetujui_oleh', 'id_user');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status_approval', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status_approval', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('status_approval', 'rejected');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    protected $table = 'kelas';

    // Add fillable if you want mass assignment
    protected $fillable = ['nama_kelas'];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tugas extends Model
{
    protected $table = 'tugas';

    protected $primaryKey = 'id_tugas';

    protected $fillable = ['judul', 'deskripsi', 'due_date'];
}

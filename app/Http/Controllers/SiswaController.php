<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SiswaController extends Controller
{
    public function dashboard()
    {
        // Cek apakah user adalah siswa
        if (auth()->user()->role !== 'siswa') {
            abort(403, 'Unauthorized');
        }
        
        return view('siswa.dashboard');
    }
}

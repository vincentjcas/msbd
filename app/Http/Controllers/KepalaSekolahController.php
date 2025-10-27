<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class KepalaSekolahController extends Controller
{
    public function dashboard()
    {
        if (!auth()->check() || auth()->user()->role !== 'kepala_sekolah') {
            abort(403, 'Unauthorized');
        }

        return view('kepala_sekolah.dashboard');
    }
}

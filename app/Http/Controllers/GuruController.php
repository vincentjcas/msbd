<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GuruController extends Controller
{
    public function dashboard()
    {
        // Cek apakah user adalah guru
        if (auth()->user()->role !== 'guru') {
            abort(403, 'Unauthorized');
        }
        
        return view('guru.dashboard');
    }
}

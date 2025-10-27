<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StafController extends Controller
{
    public function dashboard()
    {
        if (!auth()->check() || auth()->user()->role !== 'staf') {
            abort(403, 'Unauthorized');
        }

        return view('staf.dashboard');
    }
}

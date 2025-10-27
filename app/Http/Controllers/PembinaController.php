<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PembinaController extends Controller
{
    public function dashboard()
    {
        if (!auth()->check() || auth()->user()->role !== 'pembina') {
            abort(403, 'Unauthorized');
        }

        return view('pembina.dashboard');
    }
}

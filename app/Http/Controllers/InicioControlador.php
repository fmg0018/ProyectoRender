<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class InicioControlador extends Controller
{
    public function index(Request $request)
    {
        if ($request->user()) {
            return redirect()->route('dashboard');
        }

    return view('welcome');
    }
}

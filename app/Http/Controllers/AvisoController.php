<?php

namespace App\Http\Controllers;

use App\Models\Aviso;

class AvisoController extends Controller
{
    public function index()
    {
        $avisos = Aviso::latest()->get();
        return view('avisos.index', compact('avisos'));
    }
}

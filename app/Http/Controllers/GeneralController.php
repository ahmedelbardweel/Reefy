<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GeneralController extends Controller
{
    public function welcome()
    {
        return view('welcome');
    }

    public function toggleTheme()
    {
        session(['theme' => session('theme', 'light') === 'light' ? 'dark' : 'light']);
        return back();
    }
}

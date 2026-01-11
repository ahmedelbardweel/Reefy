<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        } elseif ($user->role === 'expert') {
            return redirect()->route('expert.dashboard');
        }

        // Default to farmer dashboard for everyone else
        return redirect()->route('farmer.dashboard');
    }
}

<?php

namespace App\Http\Controllers\Expert;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Consultation;

class ExpertDashboardController extends Controller
{
    public function index()
    {
        $pendingCount = Consultation::where('status', 'pending')->count();
        $answeredCount = auth()->user()->expertAdvice()->count();
        $recentConsultations = Consultation::where('status', 'pending')->latest()->take(5)->get();

        return view('expert.dashboard', compact('pendingCount', 'answeredCount', 'recentConsultations'));
    }
}

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
    public function adminDashboard(Request $request)
    {
        $search = $request->input('search');

        $farmersQuery = \App\Models\User::where('role', 'farmer');
        $expertsQuery = \App\Models\User::where('role', 'expert');
        $postsQuery = \App\Models\Post::with('user');

        if ($search) {
            $farmersQuery->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")->orWhere('email', 'like', "%{$search}%");
            });
            $expertsQuery->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")->orWhere('email', 'like', "%{$search}%");
            });
            $postsQuery->where('content', 'like', "%{$search}%");
        }

        $farmersCount = \App\Models\User::where('role', 'farmer')->count();
        $expertsCount = \App\Models\User::where('role', 'expert')->count();
        
        $farmers = $farmersQuery->latest()->paginate(10, ['*'], 'farmers_page')->withQueryString();
        $experts = $expertsQuery->latest()->paginate(10, ['*'], 'experts_page')->withQueryString();
        $recentPosts = $postsQuery->latest()->paginate(10, ['*'], 'posts_page')->withQueryString();

        // Fetch latest 3 for Overview
        $latestFarmers = \App\Models\User::where('role', 'farmer')->latest()->take(3)->get();
        $latestExperts = \App\Models\User::where('role', 'expert')->latest()->take(3)->get();
        $latestPosts = \App\Models\Post::with('user')->latest()->take(3)->get();

        return view('admin.dashboard', compact(
            'farmersCount', 'expertsCount', 'farmers', 'experts', 'recentPosts', 
            'search', 'latestFarmers', 'latestExperts', 'latestPosts'
        ));
    }
}

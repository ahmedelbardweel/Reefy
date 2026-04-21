<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class ExpertProfileController extends Controller
{
    /**
     * Display the specified expert profile.
     */
    public function show($id)
    {
        $user = User::with('expertProfile', 'expertTips')->findOrFail($id);

        if ($user->role !== 'expert') {
            abort(404);
        }

        return view('expert.profile.show', compact('user'));
    }
}

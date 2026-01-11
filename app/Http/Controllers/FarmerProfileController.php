<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FarmerProfileController extends Controller
{
    public function edit()
    {
        // Ensure profile exists or create it
        $profile = auth()->user()->farmerProfile()->firstOrCreate([]);
        return view('farmer.profile.edit', compact('profile'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'national_id_image' => 'nullable|image',
            'farm_document_image' => 'nullable|image',
            'national_id' => 'required|string',
            'bio' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
        ]);

        $user = auth()->user();
        // Ensure profile exists
        $profile = $user->farmerProfile()->firstOrCreate([]);

        if ($request->hasFile('national_id_image')) {
            $path = $request->file('national_id_image')->store('verification', 'public');
            $profile->national_id_image = $path;
        }

        if ($request->hasFile('farm_document_image')) {
            $path = $request->file('farm_document_image')->store('verification', 'public');
            $profile->farm_document_image = $path;
        }

        $profile->national_id = $request->national_id;
        $profile->bio = $request->bio;
        $profile->city = $request->city;
        $profile->country = $request->country;
        $profile->save();

        // Automatically activate user account after profile setup
        $user->status = 'active';
        $user->save();

        return redirect()->route('farmer.dashboard')->with('success', 'تم تحديث الملف الشخصي وتفعيل الحساب بنجاح!');
    }
    public function show($id)
    {
        $user = \App\Models\User::with('farmerProfile', 'crops')->findOrFail($id);
        
        if ($user->role !== 'farmer') {
            abort(404);
        }

        return view('farmer.profile.show', compact('user'));
    }
}

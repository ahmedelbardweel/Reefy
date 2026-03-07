<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    /**
     * Get the authenticated user's profile
     */
    public function index(Request $request)
    {
        $user = $request->user();
        
        // Append full URLs for images
        $user->avatar_url = $user->avatar ? asset($user->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&background=84cc16&color=fff';
        $user->cover_url = $user->cover_image ? asset($user->cover_image) : null;
        
        return response()->json([
            'status' => 'success',
            'user' => $user
        ]);
    }

    /**
     * Update the user's profile information
     */
    public function update(Request $request)
    {
        $user = $request->user();
        
        $validator = Validator::make($request->all(), [
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'email' => ['sometimes', 'required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => ['sometimes', 'required', 'string', 'min:8', 'confirmed'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $validator->validated();
        
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        $user->update($data);

        return response()->json([
            'status' => 'success',
            'message' => 'تم تحديث الملف الشخصي بنجاح',
            'user' => $user
        ]);
    }

    /**
     * Update profile and cover images
     */
    public function updateImages(Request $request)
    {
        $user = $request->user();
        
        $validator = Validator::make($request->all(), [
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'cover_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:5120'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        // Handle Avatar Upload
        if ($request->hasFile('avatar')) {
            if ($user->avatar && file_exists(public_path($user->avatar))) {
                @unlink(public_path($user->avatar));
            }
            
            $file = $request->file('avatar');
            $filename = time() . '_avatar_' . $user->id . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('profiles/avatars'), $filename);
            $user->avatar = 'profiles/avatars/' . $filename;
        }

        // Handle Cover Image Upload
        if ($request->hasFile('cover_image')) {
            if ($user->cover_image && file_exists(public_path($user->cover_image))) {
                @unlink(public_path($user->cover_image));
            }
            
            $file = $request->file('cover_image');
            $filename = time() . '_cover_' . $user->id . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('profiles/covers'), $filename);
            $user->cover_image = 'profiles/covers/' . $filename;
        }

        $user->save();

        return response()->json([
            'status' => 'success',
            'message' => 'تم تحديث الصور بنجاح',
            'avatar_url' => $user->avatar ? asset($user->avatar) : null,
            'cover_url' => $user->cover_image ? asset($user->cover_image) : null,
        ]);
    }
}

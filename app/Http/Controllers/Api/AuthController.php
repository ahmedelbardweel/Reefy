<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\ApiController as ApiController;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends ApiController
{
    /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required',
            'c_password' => 'required|same:password',
            'role' => 'required|in:farmer,expert',
        ]);

        if($validator->fails()){
            return $this->errorResponse('Validation Error.', $validator->errors(), 422);       
        }

        $input = $request->all();
        $input['password'] = Hash::make($input['password']);
        
        // Default status
        $input['status'] = 'pending'; // Default pending until profile completion

        $user = User::create($input);
        
        // Initialize appropriate profile based on role
        if ($user->role === 'farmer') {
            $user->farmerProfile()->create();
        } elseif ($user->role === 'expert') {
            $user->expertProfile()->create();
        }

        $success['token'] =  $user->createToken('ReefyApp')->plainTextToken;
        $success['name'] =  $user->name;
        $success['role'] =  $user->role;

        return $this->successResponse($success, 'User register successfully.');
    }

    /**
     * Login api
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){ 
            $user = Auth::user(); 
            $success['token'] =  $user->createToken('ReefyApp')->plainTextToken; 
            $success['name'] =  $user->name;
            $success['role'] =  $user->role;
            $success['id'] = $user->id;
   
            return $this->successResponse($success, 'User login successfully.');
        } 
        else{ 
            return $this->errorResponse('Unauthorised.', ['error'=>'Unauthorised'], 401);
        } 
    }

    /**
     * Logout api
     *
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request) {
        $request->user()->currentAccessToken()->delete();
        return $this->successResponse([], 'User logged out successfully.');
    }

    /**
     * Get authenticated user profile
     *
     * @return \Illuminate\Http\Response
     */
    public function profile(Request $request) {
        $user = $request->user();
        $user->load(['farmerProfile', 'expertProfile']); // Eager load profile data
        return $this->successResponse($user, 'User profile retrieved successfully.');
    }
}

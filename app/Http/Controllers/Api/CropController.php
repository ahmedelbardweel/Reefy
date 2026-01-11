<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\ApiController as ApiController;
use Illuminate\Http\Request;
use App\Models\Crop;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class CropController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $crops = auth()->user()->crops;
        return $this->successResponse($crops, 'Crops retrieved successfully.');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'variety' => 'nullable|string|max:255',
            'planting_date' => 'required|date',
            'area_size' => 'required|numeric',
            'area_unit' => 'required|string',
            'expected_harvest_date' => 'required|date',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Validation Error.', $validator->errors(), 422);
        }

        $input = $request->all();
        $input['user_id'] = auth()->id();
        $input['status'] = 'active';
        $input['growth_stage'] = 'seedling';
        $input['health_status'] = 'good';

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('crops', 'public');
            $input['image_path'] = $path;
        }

        $crop = Crop::create($input);

        return $this->successResponse($crop, 'Crop created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $crop = Crop::find($id);

        if (is_null($crop)) {
            return $this->errorResponse('Crop not found.');
        }

        // Check ownership
        if ($crop->user_id !== auth()->id()) {
            return $this->errorResponse('Unauthorized.', [], 403);
        }

        return $this->successResponse($crop, 'Crop retrieved successfully.');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $crop = Crop::find($id);

        if (is_null($crop)) {
            return $this->errorResponse('Crop not found.');
        }

        if ($crop->user_id !== auth()->id()) {
            return $this->errorResponse('Unauthorized.', [], 403);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'string|max:255',
            'type' => 'string|max:255',
            'variety' => 'nullable|string|max:255',
            'planting_date' => 'date',
            'area_size' => 'numeric',
            'area_unit' => 'string',
            'expected_harvest_date' => 'date',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Validation Error.', $validator->errors(), 422);
        }

        $input = $request->all();

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($crop->image_path) {
                Storage::disk('public')->delete($crop->image_path);
            }
            $path = $request->file('image')->store('crops', 'public');
            $input['image_path'] = $path;
        }

        $crop->update($input);

        return $this->successResponse($crop, 'Crop updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $crop = Crop::find($id);

        if (is_null($crop)) {
            return $this->errorResponse('Crop not found.');
        }

        if ($crop->user_id !== auth()->id()) {
            return $this->errorResponse('Unauthorized.', [], 403);
        }

        if ($crop->image_path) {
            Storage::disk('public')->delete($crop->image_path);
        }
        
        $crop->delete();

        return $this->successResponse([], 'Crop deleted successfully.');
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\ApiController as ApiController;
use Illuminate\Http\Request;
use App\Models\ExpertTip;
use Illuminate\Support\Facades\Validator;

/**
 * كونترولر نصائح الخبراء API - Expert Tip API Controller
 * 
 * العلاقات:
 * - ExpertTip: belongsTo User (Expert)
 */
class ExpertTipController extends ApiController
{
    /**
     * عرض جميع النصائح
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $tips = ExpertTip::with('user:id,name,role')->latest()->paginate(10);
        return $this->successResponse($tips, 'Expert tips retrieved.');
    }

    /**
     * إنشاء نصيحة جديدة (للخبراء فقط)
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        if (auth()->user()->role !== 'expert') {
            return $this->errorResponse('Unauthorized.', [], 403);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Validation Error.', $validator->errors(), 422);
        }

        $tip = auth()->user()->expertTips()->create($request->all());

        return $this->successResponse($tip, 'Tip created successfully.');
    }

    /**
     * عرض نصيحة معينة
     * 
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $tip = ExpertTip::with('user:id,name,role')->find($id);
        
        if (!$tip) {
            return $this->errorResponse('Tip not found.');
        }

        return $this->successResponse($tip, 'Tip retrieved.');
    }

    /**
     * تحديث نصيحة
     * 
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $tip = ExpertTip::find($id);

        if (!$tip) {
            return $this->errorResponse('Tip not found.');
        }

        if ($tip->user_id !== auth()->id()) {
            return $this->errorResponse('Unauthorized.', [], 403);
        }

        $tip->update($request->all());

        return $this->successResponse($tip, 'Tip updated successfully.');
    }

    /**
     * حذف نصيحة
     * 
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $tip = ExpertTip::find($id);

        if (!$tip) {
            return $this->errorResponse('Tip not found.');
        }

        if ($tip->user_id !== auth()->id()) {
            return $this->errorResponse('Unauthorized.', [], 403);
        }

        $tip->delete();

        return $this->successResponse([], 'Tip deleted successfully.');
    }
}

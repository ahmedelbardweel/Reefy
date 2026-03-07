<?php

namespace App\Services;

use App\Models\Crop;
use Exception;

class AnalyticsService
{
    /**
     * Update the growth percentage of a crop.
     * Triggered by: "تحديث نسبة النمو"
     * 
     * @param int $cropId
     * @param int $percentage
     * @return Crop
     * @throws Exception
     */
    public function updateGrowthRatio(int $cropId, int $percentage): Crop
    {
        $crop = Crop::findOrFail($cropId);
        
        // Ensure percentage is between 0 and 100
        $percentage = max(0, min(100, $percentage));

        $crop->update([
            'growth_percentage' => $percentage,
        ]);

        // Logic expansion: Automatically update growth stage based on percentage
        // This is already handled by an accessor in the Crop model, 
        // but we could also persists it if there was a field for it.

        return $crop;
    }

    /**
     * Calculate average growth across all crops for a user.
     */
    public function getUserGrowthAnalytics(int $userId): array
    {
        $crops = Crop::where('user_id', $userId)->get();
        
        if ($crops->isEmpty()) {
            return [
                'average_growth' => 0,
                'total_crops' => 0,
            ];
        }

        return [
            'average_growth' => $crops->avg('growth_percentage'),
            'total_crops' => $crops->count(),
        ];
    }
}

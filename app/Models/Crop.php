<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Crop extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'type',
        'area',
        'soil_type',
        'irrigation_method',
        'seed_source',
        'yield_estimate',
        'planting_date',
        'expected_harvest_date',
        'status',
        'growth_percentage',
        'notes',
        'image_path',
        'growth_stage',
        'health_status',
        'variety',
        'description',
    ];

    protected $appends = ['growth_stage_label', 'status_label', 'status_color', 'image_url'];

    public function getImageUrlAttribute()
    {
        return $this->image_path ? asset('storage/' . $this->image_path) : null;
    }

    public function getStatusLabelAttribute()
    {
        switch ($this->status) {
            case 'growing':
            case 'active': return 'قيد النمو';
            case 'harvested': return 'تم الحصاد';
            case 'dormant': return 'خامل';
            default: return 'غير محدد';
        }
    }

    public function getStatusColorAttribute()
    {
        switch ($this->status) {
            case 'growing':
            case 'active': return 'primary';
            case 'harvested': return 'success';
            default: return 'secondary';
        }
    }

    protected $casts = [
        'planting_date' => 'date',
        'expected_harvest_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function images()
    {
        return $this->hasMany(CropImage::class);
    }

    public function getGrowthStageLabelAttribute()
    {
        $p = $this->growth_percentage;
        if ($p <= 10) return 'بادرة';
        if ($p <= 40) return 'نمو خضري';
        if ($p <= 70) return 'إزهار';
        if ($p < 100) return 'ثمر';
        return 'جاهز للحصاد';
    }

    public function getDaysUntilHarvestAttribute()
    {
        if (!$this->expected_harvest_date) return 0;
        $diff = now()->diffInDays($this->expected_harvest_date, false);
        return $diff > 0 ? $diff : 0;
    }
}

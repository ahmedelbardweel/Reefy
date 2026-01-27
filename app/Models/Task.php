<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'crop_id',
        'title',
        'type',
        'due_date',
        'due_time',
        'priority',
        'reminder_time',
        'status',
        'notes',
        'water_amount',
        'duration_minutes',
        'material_name',
        'dosage',
        'dosage_unit',
        'harvest_quantity',
        'harvest_unit',
        'system_notes',
    ];

    protected $appends = ['due_datetime_utc', 'due_at'];

    public function getDueDatetimeUtcAttribute()
    {
        if (!$this->due_date) return null;
        
        $date = $this->due_date->format('Y-m-d');
        $time = $this->due_time ?: '00:00:00';
        
        // Return as ISO 8601 for full professional compatibility
        return \Carbon\Carbon::parse("$date $time", 'UTC')->toIso8601String();
    }

    /**
     * Helper to return combined date and time if the app still uses due_date
     * This will make the app show the real time instead of 12:00 AM
     */
    public function getDueAtAttribute()
    {
        $time = $this->due_time ?: '00:00:00';
        return $this->due_date->format('Y-m-d') . ' ' . $time;
    }

    protected $casts = [
        'due_date' => 'date',
    ];

    public function crop()
    {
        return $this->belongsTo(Crop::class);
    }
}

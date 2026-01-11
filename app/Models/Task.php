<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'crop_id',
        'title',
        'type',
        'due_date',
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

    protected $casts = [
        'due_date' => 'date',
    ];

    public function crop()
    {
        return $this->belongsTo(Crop::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CropImage extends Model
{
    use HasFactory;

    protected $fillable = ['crop_id', 'image_path'];

    protected $appends = ['image_url'];

    public function getImageUrlAttribute()
    {
        return $this->image_path ? asset('storage/' . $this->image_path) : null;
    }

    public function crop()
    {
        return $this->belongsTo(Crop::class);
    }
}

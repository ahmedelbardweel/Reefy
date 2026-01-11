<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CropImage extends Model
{
    use HasFactory;

    protected $fillable = ['crop_id', 'image_path'];

    public function crop()
    {
        return $this->belongsTo(Crop::class);
    }
}

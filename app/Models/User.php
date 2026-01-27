<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'status',
        'fcm_token',
        'avatar',
        'cover_image',
    ];

    public function farmerProfile()
    {
        return $this->hasOne(FarmerProfile::class);
    }

    public function expertProfile()
    {
        return $this->hasOne(ExpertProfile::class);
    }

    public function farmDetails()
    {
        return $this->hasOne(FarmDetails::class);
    }

    public function crops()
    {
        return $this->hasMany(Crop::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function consultations()
    {
        return $this->hasMany(Consultation::class);
    }

    public function expertAdvice()
    {
        return $this->hasMany(Consultation::class, 'expert_id');
    }

    public function expertTips()
    {
        return $this->hasMany(ExpertTip::class);
    }
    public function products()
    {
        return $this->hasMany(Product::class);
    }


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Get user initials (First letter of first and last name)
     */
    public function getInitialsAttribute()
    {
        $words = explode(' ', $this->name);
        if (count($words) >= 2) {
            return mb_strtoupper(mb_substr($words[0], 0, 1) . mb_substr(end($words), 0, 1));
        }
        return mb_strtoupper(mb_substr($words[0], 0, 1));
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Field yang dapat diisi secara massal (Mass Assignment).
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'organization_name',
        'avatar',
        'google_id',
    ];

    /**
     * Field yang disembunyikan saat serialisasi JSON.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Casting tipe data atribut.
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // ==========================================
    // HELPER ROLE CHECKS (STEP 31)
    // ==========================================

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isOrganizer(): bool
    {
        return $this->role === 'organizer';
    }

    // ==========================================
    // RELASI MODEL (STEP 31)
    // ==========================================

    /**
     * Relasi ke Event yang dibuat oleh Organizer/HIMA.
     */
    public function events()
    {
        return $this->hasMany(Event::class);
    }

    public function organizerReviews()
    {
        return $this->hasMany(Review::class, 'organizer_id');
    }

    public function averageRating()
    {
        return round($this->organizerReviews()->avg('rating') ?? 0, 1);
    }
}

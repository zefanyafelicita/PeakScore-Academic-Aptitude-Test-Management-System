<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // Fields that we're allowed to fill from forms
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin',
    ];

    // Keep these safe and away from API responses or debug dumps
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Cast data types so they behave the way we want
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed', // Handles password hashing automatically
            'is_admin'          => 'boolean', // So we can treat it as true/false
        ];
    }

    // --- RELATIONSHIP: USER CAN HAVE MULTIPLE TEST ATTEMPTS ---
    public function testResults(): HasMany
    {
        return $this->hasMany(TestResult::class);
    }
}

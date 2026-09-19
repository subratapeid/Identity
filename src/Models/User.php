<?php

namespace Pagelyne\Identity\Models;

use Pagelyne\Identity\Models\Concerns\HasUuid;
use Pagelyne\Identity\Database\Factories\UserFactory;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable([
    'uuid',
    'name',
    'email',
    'mobile',
    'password',
    'email_verified_at',
    'mobile_verified_at',
    'two_factor_enabled',
    'status',
    'last_login_at',
    'last_login_ip',
    'password_changed_at',
    'locked_until',
    'failed_login_attempts',
    'created_by',
    'updated_by',
])]

#[Hidden([
    'password',
    'remember_token',
])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory;
    use Notifiable;
    use HasUuid;

    protected static function newFactory(): UserFactory
    {
        return UserFactory::new();
    }
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'mobile_verified_at' => 'datetime',
            'last_login_at' => 'datetime',
            'password_changed_at' => 'datetime',
            'locked_until' => 'datetime',

            'two_factor_enabled' => 'boolean',

            'password' => 'hashed',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function profile()
    {
        return $this->hasOne(UserProfile::class);
    }

    public function loginOtps()
    {
        return $this->hasMany(UserLoginOtp::class);
    }

    public function loginAttempts()
    {
        return $this->hasMany(UserLoginAttempt::class);
    }

    public function loginLogs()
    {
        return $this->hasMany(UserLoginLog::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function createdUsers()
    {
        return $this->hasMany(User::class, 'created_by');
    }

    public function updatedUsers()
    {
        return $this->hasMany(User::class, 'updated_by');
    }
}
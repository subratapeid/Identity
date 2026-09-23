<?php

declare(strict_types=1);

namespace Pagelyne\Identity\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Pagelyne\Identity\Database\Factories\UserFactory;
use Pagelyne\Identity\Models\Concerns\HasUuid;
use Pagelyne\Identity\Services\DataEncryptionService;

#[Fillable([
    'uuid',
    'username',
    'email_hash',
    'email_encrypted',
    'phone_hash',
    'phone_encrypted',
    'password',
    'email_verified_at',
    'phone_verified_at',
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
            'phone_verified_at' => 'datetime',
            'last_login_at' => 'datetime',
            'password_changed_at' => 'datetime',
            'locked_until' => 'datetime',

            'two_factor_enabled' => 'boolean',

            'password' => 'hashed',
        ];
    }

    protected function email(): Attribute
    {
        return Attribute::make(
            get: function (): ?string {

                $value = $this->getRawOriginal('email_encrypted');

                if ($value === null || $value === '') {
                    return $value;
                }

                return app(DataEncryptionService::class)
                    ->decrypt($value);
            },

            set: function (?string $value): ?string {
                if ($value === null || $value === '') {
                    return $value;
                }

                $decrypted = app(DataEncryptionService::class)
                    ->encrypt($value);

                return $decrypted;
            },
        );
    }

    protected function phone(): Attribute
    {
        return Attribute::make(
            get: function ($value, array $attributes): ?string {
                $value = $attributes['phone_encrypted'] ?? null;

                if ($value === null || $value === '') {
                    return $value;
                }

                return app(DataEncryptionService::class)->decrypt($value);
            },

            set: function (?string $value): void {
                $this->attributes['phone_encrypted'] = $value === null || $value === ''
                    ? $value
                    : app(DataEncryptionService::class)->encrypt($value);
            },
        );
    }

    public function profile()
    {
        return $this->hasOne(UserProfile::class);
    }

    public function loginOtps()
    {
        return $this->hasMany(UserVerification::class);
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
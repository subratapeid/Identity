<?php

namespace Pagelyne\Identity\Models;

use Pagelyne\Identity\Models\Concerns\HasUuid;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'uuid',
    'user_id',
    'purpose',
    'otp_hash',
    'expires_at',
    'verified_at',
    'failed_attempts',
    'ip_address',
    'user_agent',
])]
class UserLoginAttempt extends Model
{
    use HasUuid;

    protected function casts(): array
    {
        return [
            'expires_at' => 'datetime',
            'verified_at' => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
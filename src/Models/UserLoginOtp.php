<?php

namespace Pagelyne\Identity\Models;

use Pagelyne\Identity\Models\Concerns\HasUuid;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'uuid',
    'user_id',
    'otp_hash',
    'expires_at',
    'verified_at',
    'attempts',
    'ip_address',
    'user_agent',
])]
class UserLoginOtp extends Model
{
    use HasUuid;

    public $timestamps = true;

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
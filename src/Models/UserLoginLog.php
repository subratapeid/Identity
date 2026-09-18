<?php

namespace Identity\Models;

use App\Models\Concerns\HasUuid;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'uuid',
    'user_id',
    'login_attempt_uuid',
    'email',
    'auth_method',
    'status',
    'failure_reason',
    'ip_address',
    'user_agent',
    'logged_in_at',
])]
class UserLoginLog extends Model
{
    use HasUuid;

    public $timestamps = false;

    protected function casts(): array
    {
        return [
            'logged_in_at' => 'datetime',
            'created_at' => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function loginAttempt()
    {
        return $this->belongsTo(
            UserLoginAttempt::class,
            'login_attempt_uuid',
            'uuid'
        );
    }
}
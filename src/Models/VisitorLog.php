<?php

namespace Pagelyne\Identity\Models;

use Pagelyne\Identity\Models\Concerns\HasUuid;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'uuid',
    'session_id',
    'ip_address',
    'user_agent',
    'referer',
    'landing_url',
    'current_url',
    'method',
    'country',
    'state',
    'city',
    'device',
    'browser',
    'operating_system',
    'is_bot',
    'visited_at',
])]
class VisitorLog extends Model
{
    use HasUuid;
    use HasFactory;
    protected function casts(): array
    {
        return [
            'is_bot' => 'boolean',
            'visited_at' => 'datetime',
        ];
    }
}
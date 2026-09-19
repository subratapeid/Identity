<?php

namespace Pagelyne\Identity\Models;

use Pagelyne\Identity\Models\Concerns\HasUuid;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'uuid',
    'user_id',
    'date_of_birth',
    'gender',
    'profile_photo',
    'timezone',
    'bio',
])]
class UserProfile extends Model
{
    use HasUuid;

    protected function casts(): array
    {
        return [
            'date_of_birth' => 'date',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
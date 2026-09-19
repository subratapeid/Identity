<?php

namespace Pagelyne\Identity\Models\Concerns;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

trait HasUuid
{
    protected static function bootHasUuid(): void
    {
        static::creating(function (Model $model): void {
            $model->uuid ??= (string) Str::uuid();
        });
    }
}
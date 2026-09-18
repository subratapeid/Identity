<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('personal_access_tokens', function (Blueprint $table) {

            /*
            |--------------------------------------------------------------------------
            | Primary Key
            |--------------------------------------------------------------------------
            */

            $table->id();

            /*
            |--------------------------------------------------------------------------
            | Public Token Identity
            |--------------------------------------------------------------------------
            |
            | Useful when managing tokens through an API or admin panel.
            |
            */

            $table->uuid('uuid')
                ->unique();

            /*
            |--------------------------------------------------------------------------
            | Token Owner
            |--------------------------------------------------------------------------
            |
            | Polymorphic relationship keeps this compatible with Laravel
            | Sanctum and allows other authenticatable models in the future.
            |
            */

            $table->morphs('tokenable');

            /*
            |--------------------------------------------------------------------------
            | Token Name
            |--------------------------------------------------------------------------
            |
            | Example:
            |
            | "My Laptop"
            | "Mobile App"
            | "CRM Integration"
            | "Accounting API"
            |
            */

            $table->string('name', 100);

            /*
            |--------------------------------------------------------------------------
            | Token
            |--------------------------------------------------------------------------
            |
            | Store only the hashed token.
            |
            | Never store the raw API token in the database.
            |
            | This column is intentionally named "token" for Laravel
            | Sanctum compatibility.
            |
            */

            $table->char('token', 64)
                ->unique();

            /*
            |--------------------------------------------------------------------------
            | Abilities / Scopes
            |--------------------------------------------------------------------------
            |
            | Example:
            |
            | [
            |     "orders:read",
            |     "orders:create",
            |     "profile:read"
            | ]
            |
            */

            $table->json('abilities')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | Device Reference
            |--------------------------------------------------------------------------
            |
            | Links the API token to a known device when applicable.
            |
            */

            $table->uuid('device_uuid')
                ->nullable()
                ->index();

            /*
            |--------------------------------------------------------------------------
            | Token State
            |--------------------------------------------------------------------------
            */

            $table->boolean('is_active')
                ->default(true)
                ->index();

            $table->timestamp('revoked_at')
                ->nullable();

            $table->string('revoked_reason', 100)
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | Usage Tracking
            |--------------------------------------------------------------------------
            */

            $table->timestamp('last_used_at')
                ->nullable()
                ->index();

            /*
            |--------------------------------------------------------------------------
            | Expiration
            |--------------------------------------------------------------------------
            |
            | NULL means the token does not have a fixed expiration date.
            |
            */

            $table->timestamp('expires_at')
                ->nullable()
                ->index();

            /*
            |--------------------------------------------------------------------------
            | Request Metadata
            |--------------------------------------------------------------------------
            |
            | Useful for security and API auditing.
            |
            */

            $table->ipAddress('last_used_ip')
                ->nullable();

            $table->text('last_used_user_agent')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | Additional Metadata
            |--------------------------------------------------------------------------
            |
            | Only store non-sensitive contextual information here.
            |
            | Never store raw tokens, passwords, secrets or credentials.
            |
            */

            $table->json('metadata')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | Timestamps
            |--------------------------------------------------------------------------
            */

            $table->timestamps();

            /*
            |--------------------------------------------------------------------------
            | Indexes
            |--------------------------------------------------------------------------
            */

            $table->index([
                'tokenable_type',
                'tokenable_id',
                'is_active',
            ], 'tokenable_active_index');

            $table->index([
                'tokenable_type',
                'tokenable_id',
                'expires_at',
            ], 'tokenable_expires_index');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('personal_access_tokens');
    }
};
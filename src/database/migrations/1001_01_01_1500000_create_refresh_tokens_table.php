<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('refresh_tokens', function (Blueprint $table) {

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
            */

            $table->uuid('uuid')
                ->unique();

            /*
            |--------------------------------------------------------------------------
            | User
            |--------------------------------------------------------------------------
            */

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            /*
            |--------------------------------------------------------------------------
            | API Client
            |--------------------------------------------------------------------------
            |
            | api_clients will be created separately.
            |
            */

            $table->uuid('client_uuid')
                ->nullable()
                ->index();

            /*
            |--------------------------------------------------------------------------
            | Token Hash
            |--------------------------------------------------------------------------
            |
            | Store only the hash of the refresh token.
            |
            | Never store the raw refresh token.
            |
            */

            $table->char('token_hash', 64)
                ->unique();

            /*
            |--------------------------------------------------------------------------
            | Token Family
            |--------------------------------------------------------------------------
            |
            | Used for refresh-token rotation.
            |
            | Every token generated from the same login/authentication flow
            | belongs to the same family.
            |
            */

            $table->uuid('family_uuid')
                ->index();

            /*
            |--------------------------------------------------------------------------
            | Parent Token
            |--------------------------------------------------------------------------
            |
            | When a refresh token is rotated, the new token can reference
            | the previous token.
            |
            */

            $table->uuid('parent_uuid')
                ->nullable()
                ->index();

            /*
            |--------------------------------------------------------------------------
            | Device
            |--------------------------------------------------------------------------
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

            /*
            |--------------------------------------------------------------------------
            | Expiration
            |--------------------------------------------------------------------------
            */

            $table->timestamp('expires_at')
                ->index();

            /*
            |--------------------------------------------------------------------------
            | Usage
            |--------------------------------------------------------------------------
            */

            $table->timestamp('last_used_at')
                ->nullable()
                ->index();

            /*
            |--------------------------------------------------------------------------
            | Rotation
            |--------------------------------------------------------------------------
            */

            $table->timestamp('rotated_at')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | Revocation
            |--------------------------------------------------------------------------
            */

            $table->timestamp('revoked_at')
                ->nullable();

            $table->string('revoked_reason', 100)
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | Security Information
            |--------------------------------------------------------------------------
            */

            $table->ipAddress('created_ip')
                ->nullable();

            $table->ipAddress('last_used_ip')
                ->nullable();

            $table->text('user_agent')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | Additional Metadata
            |--------------------------------------------------------------------------
            |
            | Never store raw tokens, passwords, API secrets or other
            | authentication credentials here.
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
                'user_id',
                'is_active',
            ]);

            $table->index([
                'user_id',
                'expires_at',
            ]);

            $table->index([
                'user_id',
                'family_uuid',
            ]);

            $table->index([
                'client_uuid',
                'is_active',
            ]);

            $table->index([
                'device_uuid',
                'is_active',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('refresh_tokens');
    }
};
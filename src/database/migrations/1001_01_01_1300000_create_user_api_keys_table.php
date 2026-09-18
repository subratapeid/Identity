<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('user_api_keys', function (Blueprint $table) {

            /*
            |--------------------------------------------------------------------------
            | Primary Key
            |--------------------------------------------------------------------------
            */

            $table->id();

            /*
            |--------------------------------------------------------------------------
            | Public API Key Identity
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
            | API Key Name
            |--------------------------------------------------------------------------
            |
            | Examples:
            |
            | Website Integration
            | Mobile Application
            | ERP Integration
            | Accounting Software
            |
            */

            $table->string('name', 150);

            /*
            |--------------------------------------------------------------------------
            | API Key Identifier
            |--------------------------------------------------------------------------
            |
            | A short public identifier can safely be shown to the user.
            |
            | Example:
            |
            | pgl_live_xxxxxxxxx
            |
            | The secret itself is never stored here.
            |
            */

            $table->string('key_id', 100)
                ->unique();

            /*
            |--------------------------------------------------------------------------
            | API Secret
            |--------------------------------------------------------------------------
            |
            | Store only a hash of the secret.
            |
            | Never store the raw secret in the database.
            |
            */

            $table->char('secret_hash', 64)
                ->unique();

            /*
            |--------------------------------------------------------------------------
            | Environment
            |--------------------------------------------------------------------------
            |
            | Examples:
            |
            | live
            | test
            | development
            |
            */

            $table->string('environment', 20)
                ->default('live')
                ->index();

            /*
            |--------------------------------------------------------------------------
            | Permissions / Scopes
            |--------------------------------------------------------------------------
            |
            | Examples:
            |
            | [
            |     "users:read",
            |     "orders:read",
            |     "orders:create"
            | ]
            |
            */

            $table->json('abilities')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | IP Restrictions
            |--------------------------------------------------------------------------
            |
            | Optional list of allowed IP addresses/CIDR ranges.
            |
            */

            $table->json('allowed_ips')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | Device Reference
            |--------------------------------------------------------------------------
            */

            $table->uuid('device_uuid')
                ->nullable()
                ->index();

            /*
            |--------------------------------------------------------------------------
            | Status
            |--------------------------------------------------------------------------
            */

            $table->boolean('is_active')
                ->default(true)
                ->index();

            /*
            |--------------------------------------------------------------------------
            | Lifecycle
            |--------------------------------------------------------------------------
            */

            $table->timestamp('expires_at')
                ->nullable()
                ->index();

            $table->timestamp('last_used_at')
                ->nullable()
                ->index();

            $table->timestamp('revoked_at')
                ->nullable();

            $table->string('revoked_reason', 100)
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | Usage Information
            |--------------------------------------------------------------------------
            */

            $table->ipAddress('last_used_ip')
                ->nullable();

            $table->text('last_used_user_agent')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | Metadata
            |--------------------------------------------------------------------------
            |
            | Only non-sensitive contextual information should be stored.
            |
            | Never store:
            | - Raw API secret
            | - Password
            | - Access token
            | - Refresh token
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
                'environment',
            ]);

            $table->index([
                'user_id',
                'expires_at',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_api_keys');
    }
};
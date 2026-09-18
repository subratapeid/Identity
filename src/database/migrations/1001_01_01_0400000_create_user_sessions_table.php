<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('user_sessions', function (Blueprint $table) {

            /*
            |--------------------------------------------------------------------------
            | Primary Key
            |--------------------------------------------------------------------------
            */

            $table->id();

            /*
            |--------------------------------------------------------------------------
            | Public Session Identity
            |--------------------------------------------------------------------------
            |
            | Never expose the database ID as the public session identifier.
            |
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
            | Session Identifier
            |--------------------------------------------------------------------------
            |
            | Store a secure hash instead of storing a raw session identifier
            | where possible.
            |
            | This allows the application to identify/revoke a session without
            | keeping the sensitive session value in plaintext.
            |
            */

            $table->char('session_hash', 64)
                ->unique();

            /*
            |--------------------------------------------------------------------------
            | Session Type
            |--------------------------------------------------------------------------
            |
            | Examples:
            |
            | web
            | api
            | mobile
            | admin
            | remember_me
            |
            */

            $table->string('session_type', 30)
                ->default('web')
                ->index();

            /*
            |--------------------------------------------------------------------------
            | Device Reference
            |--------------------------------------------------------------------------
            |
            | user_devices will be created separately.
            |
            | We intentionally keep this as a UUID reference instead of a
            | database foreign key so the Identity module remains flexible
            | during module/service separation.
            |
            */

            $table->uuid('device_uuid')
                ->nullable()
                ->index();

            /*
            |--------------------------------------------------------------------------
            | Network Information
            |--------------------------------------------------------------------------
            */

            $table->ipAddress('ip_address')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | User Agent
            |--------------------------------------------------------------------------
            |
            | Browser and operating-system information.
            |
            */

            $table->text('user_agent')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | Location Information
            |--------------------------------------------------------------------------
            |
            | These values should be treated as approximate metadata.
            | They should not be considered the user's actual address.
            |
            */

            $table->string('country_code', 2)
                ->nullable()
                ->index();

            $table->string('region', 100)
                ->nullable();

            $table->string('city', 100)
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | Session Activity
            |--------------------------------------------------------------------------
            */

            $table->timestamp('last_activity_at')
                ->nullable()
                ->index();

            $table->timestamp('expires_at')
                ->nullable()
                ->index();

            /*
            |--------------------------------------------------------------------------
            | Session State
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
            | Remember Me
            |--------------------------------------------------------------------------
            */

            $table->boolean('remember_me')
                ->default(false);

            /*
            |--------------------------------------------------------------------------
            | Additional Metadata
            |--------------------------------------------------------------------------
            |
            | Keep this for non-critical session metadata that may be needed
            | by different applications.
            |
            | Do not store passwords, access tokens, refresh tokens, or other
            | secrets here.
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
            | Composite Indexes
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
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_sessions');
    }
};
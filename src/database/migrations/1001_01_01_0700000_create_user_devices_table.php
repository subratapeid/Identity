<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('user_devices', function (Blueprint $table) {

            /*
            |--------------------------------------------------------------------------
            | Primary Key
            |--------------------------------------------------------------------------
            */

            $table->id();

            /*
            |--------------------------------------------------------------------------
            | Public Device Identity
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
            | Device Identification
            |--------------------------------------------------------------------------
            |
            | device_identifier should be a generated application-level
            | identifier. Do not use hardware serial numbers or other
            | unnecessarily invasive identifiers.
            |
            */

            $table->char('device_identifier_hash', 64)
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | Device Information
            |--------------------------------------------------------------------------
            */

            $table->string('device_name', 150)
                ->nullable();

            $table->string('device_type', 30)
                ->nullable()
                ->index();

            /*
            | Examples:
            |
            | desktop
            | laptop
            | mobile
            | tablet
            | tv
            | other
            */

            $table->string('platform', 50)
                ->nullable();

            /*
            | Examples:
            |
            | Windows
            | Android
            | iOS
            | macOS
            | Linux
            */

            $table->string('platform_version', 50)
                ->nullable();

            $table->string('browser', 100)
                ->nullable();

            $table->string('browser_version', 50)
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | App Information
            |--------------------------------------------------------------------------
            |
            | Useful when the same Identity system is used by multiple
            | mobile/web applications.
            |
            */

            $table->string('app_name', 100)
                ->nullable();

            $table->string('app_version', 50)
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | Push Notification
            |--------------------------------------------------------------------------
            |
            | A device can have a push token for notifications.
            |
            | The actual token should be treated as sensitive data.
            |
            */

            $table->text('push_token_encrypted')
                ->nullable();

            $table->char('push_token_hash', 64)
                ->nullable()
                ->index();

            /*
            |--------------------------------------------------------------------------
            | Device Status
            |--------------------------------------------------------------------------
            */

            $table->boolean('is_trusted')
                ->default(false)
                ->index();

            $table->boolean('is_active')
                ->default(true)
                ->index();

            /*
            |--------------------------------------------------------------------------
            | Activity
            |--------------------------------------------------------------------------
            */

            $table->timestamp('first_seen_at')
                ->nullable();

            $table->timestamp('last_seen_at')
                ->nullable()
                ->index();

            /*
            |--------------------------------------------------------------------------
            | Security
            |--------------------------------------------------------------------------
            */

            $table->timestamp('trusted_at')
                ->nullable();

            $table->timestamp('revoked_at')
                ->nullable();

            $table->string('revoked_reason', 100)
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | Metadata
            |--------------------------------------------------------------------------
            |
            | Keep only non-sensitive device metadata here.
            |
            */

            $table->json('metadata')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | Timestamps / Soft Delete
            |--------------------------------------------------------------------------
            */

            $table->timestamps();

            $table->softDeletes();

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
                'device_type',
            ]);

            $table->index([
                'user_id',
                'last_seen_at',
            ]);

            /*
            |--------------------------------------------------------------------------
            | Prevent Duplicate Device Identifiers Per User
            |--------------------------------------------------------------------------
            */

            $table->unique([
                'user_id',
                'device_identifier_hash',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_devices');
    }
};
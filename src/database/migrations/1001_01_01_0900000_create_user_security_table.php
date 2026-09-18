<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('user_security', function (Blueprint $table) {

            /*
            |--------------------------------------------------------------------------
            | Primary Key
            |--------------------------------------------------------------------------
            */

            $table->id();

            /*
            |--------------------------------------------------------------------------
            | User
            |--------------------------------------------------------------------------
            |
            | One security record per user.
            |
            */

            $table->foreignId('user_id')
                ->unique()
                ->constrained('users')
                ->cascadeOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Login Security
            |--------------------------------------------------------------------------
            */

            $table->unsignedSmallInteger('failed_login_attempts')
                ->default(0);

            $table->timestamp('last_failed_login_at')
                ->nullable();

            $table->timestamp('locked_until')
                ->nullable()
                ->index();

            /*
            |--------------------------------------------------------------------------
            | Account Lock
            |--------------------------------------------------------------------------
            */

            $table->boolean('is_locked')
                ->default(false)
                ->index();

            $table->timestamp('locked_at')
                ->nullable();

            $table->string('lock_reason', 100)
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | Password Security
            |--------------------------------------------------------------------------
            */

            $table->timestamp('password_changed_at')
                ->nullable();

            $table->timestamp('password_expires_at')
                ->nullable()
                ->index();

            $table->boolean('password_change_required')
                ->default(false)
                ->index();

            /*
            |--------------------------------------------------------------------------
            | Password History
            |--------------------------------------------------------------------------
            |
            | This is only a quick security setting.
            |
            | Actual previous password hashes should be stored separately if
            | password-history enforcement is required.
            |
            */

            $table->unsignedTinyInteger('password_history_limit')
                ->default(5);

            /*
            |--------------------------------------------------------------------------
            | Additional Authentication
            |--------------------------------------------------------------------------
            |
            | Two-factor authentication itself belongs in:
            |
            | user_two_factor_auth
            |
            | These fields only represent the user's security preference/state.
            |
            */

            $table->boolean('two_factor_required')
                ->default(false)
                ->index();

            /*
            |--------------------------------------------------------------------------
            | Verification Requirements
            |--------------------------------------------------------------------------
            */

            $table->boolean('email_verification_required')
                ->default(true);

            $table->boolean('phone_verification_required')
                ->default(false);

            /*
            |--------------------------------------------------------------------------
            | Suspicious Activity
            |--------------------------------------------------------------------------
            */

            $table->boolean('security_alerts_enabled')
                ->default(true);

            $table->timestamp('last_security_alert_at')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | Credential Recovery
            |--------------------------------------------------------------------------
            */

            $table->timestamp('last_password_reset_at')
                ->nullable();

            $table->timestamp('last_security_verification_at')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | Session Security
            |--------------------------------------------------------------------------
            */

            $table->boolean('revoke_sessions_on_password_change')
                ->default(true);

            $table->boolean('revoke_tokens_on_password_change')
                ->default(true);

            /*
            |--------------------------------------------------------------------------
            | Security Metadata
            |--------------------------------------------------------------------------
            |
            | Keep only non-sensitive security configuration/context here.
            |
            | Never store passwords, OTPs, API tokens, refresh tokens or
            | other authentication secrets in this column.
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
            | Useful Indexes
            |--------------------------------------------------------------------------
            */

            $table->index([
                'is_locked',
                'locked_until',
            ]);

            $table->index([
                'password_change_required',
                'password_expires_at',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_security');
    }
};
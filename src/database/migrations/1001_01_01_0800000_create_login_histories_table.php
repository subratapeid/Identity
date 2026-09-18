<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('login_histories', function (Blueprint $table) {

            /*
            |--------------------------------------------------------------------------
            | Primary Key
            |--------------------------------------------------------------------------
            */

            $table->id();

            /*
            |--------------------------------------------------------------------------
            | Public Identity
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
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Device
            |--------------------------------------------------------------------------
            |
            | Stored as UUID instead of a direct foreign key so historical
            | login records can remain valid even if a device is removed.
            |
            */

            $table->uuid('device_uuid')
                ->nullable()
                ->index();

            /*
            |--------------------------------------------------------------------------
            | Authentication Event
            |--------------------------------------------------------------------------
            |
            | Examples:
            |
            | login
            | logout
            | login_failed
            | login_blocked
            | session_expired
            | session_revoked
            | password_login
            | oauth_login
            | two_factor_login
            |
            */

            $table->string('event', 40)
                ->index();

            /*
            |--------------------------------------------------------------------------
            | Authentication Method
            |--------------------------------------------------------------------------
            |
            | Examples:
            |
            | password
            | otp
            | two_factor
            | oauth
            | api_token
            | magic_link
            | passkey
            |
            */

            $table->string('authentication_method', 40)
                ->nullable()
                ->index();

            /*
            |--------------------------------------------------------------------------
            | Login Result
            |--------------------------------------------------------------------------
            */

            $table->string('status', 30)
                ->index();

            /*
            | Examples:
            |
            | success
            | failed
            | blocked
            | revoked
            | expired
            */

            /*
            |--------------------------------------------------------------------------
            | Failure Information
            |--------------------------------------------------------------------------
            |
            | Never store passwords, OTPs, tokens or other secrets here.
            |
            */

            $table->string('failure_reason', 100)
                ->nullable();

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
            */

            $table->text('user_agent')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | Approximate Location
            |--------------------------------------------------------------------------
            |
            | These are optional metadata values obtained from the IP or
            | authentication request. They should not be treated as the
            | user's actual physical address.
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
            | Session Reference
            |--------------------------------------------------------------------------
            */

            $table->uuid('session_uuid')
                ->nullable()
                ->index();

            /*
            |--------------------------------------------------------------------------
            | API Token Reference
            |--------------------------------------------------------------------------
            */

            $table->uuid('personal_access_token_uuid')
                ->nullable()
                ->index();

            /*
            |--------------------------------------------------------------------------
            | Login Timestamp
            |--------------------------------------------------------------------------
            */

            $table->timestamp('occurred_at')
                ->useCurrent()
                ->index();

            /*
            |--------------------------------------------------------------------------
            | Additional Metadata
            |--------------------------------------------------------------------------
            |
            | Store only non-sensitive contextual information.
            |
            | Never store:
            | - Passwords
            | - OTPs
            | - Access tokens
            | - Refresh tokens
            | - Session secrets
            |
            */

            $table->json('metadata')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | Created At
            |--------------------------------------------------------------------------
            |
            | Login history should normally be append-only.
            | No updated_at is required.
            |
            */

            $table->timestamp('created_at')
                ->useCurrent();

            /*
            |--------------------------------------------------------------------------
            | Indexes
            |--------------------------------------------------------------------------
            */

            $table->index([
                'user_id',
                'occurred_at',
            ]);

            $table->index([
                'user_id',
                'event',
                'occurred_at',
            ]);

            $table->index([
                'ip_address',
                'occurred_at',
            ]);

            $table->index([
                'status',
                'occurred_at',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('login_histories');
    }
};
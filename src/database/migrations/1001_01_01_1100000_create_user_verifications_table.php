<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('user_verifications', function (Blueprint $table) {

            /*
            |--------------------------------------------------------------------------
            | Primary Key
            |--------------------------------------------------------------------------
            */

            $table->id();

            /*
            |--------------------------------------------------------------------------
            | Public Verification Identity
            |--------------------------------------------------------------------------
            */

            $table->uuid('uuid')
                ->unique();

            /*
            |--------------------------------------------------------------------------
            | User
            |--------------------------------------------------------------------------
            |
            | Nullable because a verification request may sometimes happen
            | before a user account is fully created.
            |
            */

            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Verification Type
            |--------------------------------------------------------------------------
            |
            | Examples:
            |
            | email
            | phone
            | identity
            | address
            | document
            | account
            | security
            |
            */

            $table->string('type', 40)
                ->index();

            /*
            |--------------------------------------------------------------------------
            | Verification Channel
            |--------------------------------------------------------------------------
            |
            | Examples:
            |
            | email
            | sms
            | whatsapp
            | app
            | manual
            | api
            |
            */

            $table->string('channel', 30)
                ->nullable()
                ->index();

            /*
            |--------------------------------------------------------------------------
            | Target Hash
            |--------------------------------------------------------------------------
            |
            | Used to identify what is being verified without storing the
            | actual email/phone value in plaintext.
            |
            | Example:
            |
            | email → HMAC(normalized email)
            | phone → HMAC(normalized phone)
            |
            */

            $table->char('target_hash', 64)
                ->nullable()
                ->index();

            /*
            |--------------------------------------------------------------------------
            | Verification Token
            |--------------------------------------------------------------------------
            |
            | Store only a hash of the OTP/token.
            |
            | Never store the actual OTP or verification token.
            |
            */

            $table->char('token_hash', 64)
                ->nullable()
                ->unique();

            /*
            |--------------------------------------------------------------------------
            | OTP Support
            |--------------------------------------------------------------------------
            |
            | If an OTP is used, store only its hash.
            |
            | This is separate from token_hash so the system can support
            | different verification mechanisms in the future.
            |
            */

            $table->char('otp_hash', 64)
                ->nullable()
                ->index();

            /*
            |--------------------------------------------------------------------------
            | OTP / Token Attempts
            |--------------------------------------------------------------------------
            */

            $table->unsignedSmallInteger('attempts')
                ->default(0);

            $table->unsignedSmallInteger('max_attempts')
                ->default(5);

            /*
            |--------------------------------------------------------------------------
            | Verification State
            |--------------------------------------------------------------------------
            */

            $table->string('status', 30)
                ->default('pending')
                ->index();

            /*
            | Examples:
            |
            | pending
            | verified
            | expired
            | failed
            | cancelled
            | blocked
            */

            /*
            |--------------------------------------------------------------------------
            | Verification Timestamps
            |--------------------------------------------------------------------------
            */

            $table->timestamp('sent_at')
                ->nullable();

            $table->timestamp('verified_at')
                ->nullable();

            $table->timestamp('expires_at')
                ->nullable()
                ->index();

            /*
            |--------------------------------------------------------------------------
            | Security / Request Information
            |--------------------------------------------------------------------------
            */

            $table->ipAddress('request_ip')
                ->nullable();

            $table->text('user_agent')
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
            | Verification Provider
            |--------------------------------------------------------------------------
            |
            | Useful when different providers are used for email/SMS/OTP.
            |
            | Examples:
            |
            | internal
            | sms_provider
            | email_provider
            | identity_provider
            |
            */

            $table->string('provider', 50)
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | Additional Metadata
            |--------------------------------------------------------------------------
            |
            | Only non-sensitive contextual information should be stored.
            |
            | Never store:
            | - Raw OTP
            | - Raw verification token
            | - Password
            | - API credentials
            | - Other authentication secrets
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
                'type',
                'status',
            ]);

            $table->index([
                'user_id',
                'type',
                'expires_at',
            ]);

            $table->index([
                'target_hash',
                'type',
                'status',
            ]);

            $table->index([
                'user_id',
                'created_at',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_verifications');
    }
};
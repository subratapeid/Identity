<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('user_two_factor_auth', function (Blueprint $table) {

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
                ->constrained('users')
                ->cascadeOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Authentication Method
            |--------------------------------------------------------------------------
            |
            | Examples:
            |
            | totp
            | passkey
            | webauthn
            | sms
            | email
            | security_key
            |
            */

            $table->string('method', 40)
                ->index();

            /*
            |--------------------------------------------------------------------------
            | Display Name
            |--------------------------------------------------------------------------
            |
            | Example:
            |
            | "My Authenticator"
            | "Office Security Key"
            | "iPhone Passkey"
            |
            */

            $table->string('name', 100)
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | Encrypted Secret
            |--------------------------------------------------------------------------
            |
            | Mainly useful for TOTP and similar methods.
            |
            | Never store the raw secret in plaintext.
            |
            */

            $table->text('secret_encrypted')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | WebAuthn / Passkey Credential
            |--------------------------------------------------------------------------
            |
            | WebAuthn credential IDs and public keys can be long.
            |
            | The actual private key is never stored by the server.
            |
            */

            $table->text('credential_id')
                ->nullable();

            $table->text('public_key')
                ->nullable();

            $table->unsignedBigInteger('sign_count')
                ->nullable();

            $table->string('aaguid', 36)
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | WebAuthn Metadata
            |--------------------------------------------------------------------------
            |
            | Examples:
            |
            | usb
            | nfc
            | ble
            | internal
            |
            */

            $table->json('transports')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | Verification State
            |--------------------------------------------------------------------------
            */

            $table->boolean('is_verified')
                ->default(false)
                ->index();

            $table->timestamp('verified_at')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | Enabled State
            |--------------------------------------------------------------------------
            */

            $table->boolean('is_enabled')
                ->default(true)
                ->index();

            /*
            |--------------------------------------------------------------------------
            | Primary Method
            |--------------------------------------------------------------------------
            |
            | A user can have multiple authentication methods but one can
            | be marked as the preferred/default method.
            |
            */

            $table->boolean('is_primary')
                ->default(false)
                ->index();

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
            | Device Reference
            |--------------------------------------------------------------------------
            */

            $table->uuid('device_uuid')
                ->nullable()
                ->index();

            /*
            |--------------------------------------------------------------------------
            | Recovery / Revocation
            |--------------------------------------------------------------------------
            */

            $table->timestamp('revoked_at')
                ->nullable();

            $table->string('revoked_reason', 100)
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | Additional Metadata
            |--------------------------------------------------------------------------
            |
            | Keep only non-sensitive contextual information here.
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
                'method',
                'is_enabled',
            ]);

            $table->index([
                'user_id',
                'is_primary',
            ]);

            $table->index([
                'user_id',
                'last_used_at',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_two_factor_auth');
    }
};
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('oauth_accounts', function (Blueprint $table) {

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
            | OAuth Provider
            |--------------------------------------------------------------------------
            |
            | Examples:
            |
            | google
            | microsoft
            | github
            | apple
            | facebook
            |
            */

            $table->string('provider', 50)
                ->index();

            /*
            |--------------------------------------------------------------------------
            | Provider Account Identifier
            |--------------------------------------------------------------------------
            |
            | The unique user/account ID provided by the OAuth provider.
            |
            | Keep the actual provider ID encrypted and maintain a hash
            | for lookup.
            |
            */

            $table->text('provider_account_id_encrypted');

            $table->char('provider_account_id_hash', 64);

            /*
            |--------------------------------------------------------------------------
            | Provider Email
            |--------------------------------------------------------------------------
            |
            | This is the email received from the OAuth provider.
            |
            | It should not be treated as the application's primary email.
            |
            */

            $table->text('provider_email_encrypted')
                ->nullable();

            $table->char('provider_email_hash', 64)
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | Provider Profile Information
            |--------------------------------------------------------------------------
            |
            | These are optional cached values received from the provider.
            |
            | They should not become the source of truth for the user's
            | profile.
            |
            */

            $table->text('provider_name_encrypted')
                ->nullable();

            $table->text('provider_avatar_url_encrypted')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | OAuth Tokens
            |--------------------------------------------------------------------------
            |
            | Tokens must be encrypted.
            |
            | Never store OAuth access/refresh tokens as plaintext.
            |
            */

            $table->text('access_token_encrypted')
                ->nullable();

            $table->text('refresh_token_encrypted')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | Token Expiration
            |--------------------------------------------------------------------------
            */

            $table->timestamp('access_token_expires_at')
                ->nullable();

            $table->timestamp('refresh_token_expires_at')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | OAuth Scopes
            |--------------------------------------------------------------------------
            */

            $table->json('scopes')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | Provider Token Type
            |--------------------------------------------------------------------------
            |
            | Usually "Bearer", but kept flexible for future providers.
            |
            */

            $table->string('token_type', 30)
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | Provider Metadata
            |--------------------------------------------------------------------------
            |
            | Non-sensitive information returned by the provider can be
            | stored here.
            |
            | Do not store raw tokens or secrets in this column.
            |
            */

            $table->json('metadata')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | Account State
            |--------------------------------------------------------------------------
            */

            $table->boolean('is_active')
                ->default(true)
                ->index();

            $table->timestamp('connected_at')
                ->nullable();

            $table->timestamp('last_used_at')
                ->nullable()
                ->index();

            $table->timestamp('revoked_at')
                ->nullable();

            $table->string('revoked_reason', 100)
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
            | Timestamps
            |--------------------------------------------------------------------------
            */

            $table->timestamps();

            /*
            |--------------------------------------------------------------------------
            | Indexes
            |--------------------------------------------------------------------------
            */

            $table->unique([
                'provider',
                'provider_account_id_hash',
            ]);

            $table->index([
                'user_id',
                'provider',
                'is_active',
            ]);

            $table->index([
                'provider',
                'provider_email_hash',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('oauth_accounts');
    }
};
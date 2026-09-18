<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('api_clients', function (Blueprint $table) {

            /*
            |--------------------------------------------------------------------------
            | Primary Key
            |--------------------------------------------------------------------------
            */

            $table->id();

            /*
            |--------------------------------------------------------------------------
            | Public Client Identity
            |--------------------------------------------------------------------------
            */

            $table->uuid('uuid')
                ->unique();

            /*
            |--------------------------------------------------------------------------
            | Client Information
            |--------------------------------------------------------------------------
            */

            $table->string('name', 150);

            $table->string('description', 500)
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | Client Type
            |--------------------------------------------------------------------------
            |
            | Examples:
            |
            | public
            | confidential
            | first_party
            | third_party
            | service
            |
            */

            $table->string('type', 30)
                ->default('confidential')
                ->index();

            /*
            |--------------------------------------------------------------------------
            | Application / Integration Identifier
            |--------------------------------------------------------------------------
            |
            | This is safe to expose publicly.
            |
            */

            $table->string('client_id', 100)
                ->unique();

            /*
            |--------------------------------------------------------------------------
            | Client Secret
            |--------------------------------------------------------------------------
            |
            | Store only the hash of the secret.
            |
            | Never store the raw client secret.
            |
            */

            $table->char('client_secret_hash', 64)
                ->nullable()
                ->unique();

            /*
            |--------------------------------------------------------------------------
            | Owner
            |--------------------------------------------------------------------------
            |
            | Nullable because a client can be a system-level application
            | that is not owned by a specific user.
            |
            */

            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Application URL
            |--------------------------------------------------------------------------
            */

            $table->string('website_url', 500)
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | OAuth Redirect URIs
            |--------------------------------------------------------------------------
            |
            | Used when this client participates in OAuth authorization flows.
            |
            */

            $table->json('redirect_uris')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | Allowed Origins
            |--------------------------------------------------------------------------
            |
            | Useful for browser-based applications and CORS-related
            | application controls.
            |
            */

            $table->json('allowed_origins')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | Allowed IP Addresses
            |--------------------------------------------------------------------------
            |
            | Optional restriction for server-to-server clients.
            |
            */

            $table->json('allowed_ips')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | Default Abilities / Scopes
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
            | Authentication Methods
            |--------------------------------------------------------------------------
            |
            | Examples:
            |
            | [
            |     "client_secret",
            |     "authorization_code",
            |     "refresh_token"
            | ]
            |
            */

            $table->json('authentication_methods')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | Environment
            |--------------------------------------------------------------------------
            |
            | Examples:
            |
            | production
            | staging
            | development
            |
            */

            $table->string('environment', 30)
                ->default('production')
                ->index();

            /*
            |--------------------------------------------------------------------------
            | Client Status
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
            | Security
            |--------------------------------------------------------------------------
            */

            $table->timestamp('secret_rotated_at')
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
            | Only non-sensitive application metadata should be stored here.
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
                'type',
                'is_active',
            ]);

            $table->index([
                'environment',
                'is_active',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('api_clients');
    }
};
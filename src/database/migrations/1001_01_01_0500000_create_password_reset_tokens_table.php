<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('password_reset_tokens', function (Blueprint $table) {

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
            | The reset request belongs directly to a user.
            |
            */

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Reset Token
            |--------------------------------------------------------------------------
            |
            | Never store the actual reset token.
            |
            | The application generates a secure random token and stores only
            | its hash in the database.
            |
            */

            $table->char('token_hash', 64)
                ->unique();

            /*
            |--------------------------------------------------------------------------
            | Token Expiration
            |--------------------------------------------------------------------------
            */

            $table->timestamp('expires_at')
                ->index();

            /*
            |--------------------------------------------------------------------------
            | Token Usage
            |--------------------------------------------------------------------------
            |
            | NULL  = token has not been used
            | DATE  = token has already been used
            |
            */

            $table->timestamp('used_at')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | Request Information
            |--------------------------------------------------------------------------
            |
            | Useful for security monitoring and abuse detection.
            |
            */

            $table->ipAddress('request_ip')
                ->nullable();

            $table->text('user_agent')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | Request Metadata
            |--------------------------------------------------------------------------
            |
            | Keep only non-sensitive contextual information here.
            |
            | Never store passwords, raw reset tokens, authentication tokens,
            | or other secrets.
            |
            */

            $table->json('metadata')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | Timestamps
            |--------------------------------------------------------------------------
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
                'expires_at',
            ]);

            $table->index([
                'user_id',
                'used_at',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('password_reset_tokens');
    }
};
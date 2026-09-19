<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {

            /*
            |--------------------------------------------------------------------------
            | Primary Identity
            |--------------------------------------------------------------------------
            */

            $table->id();

            $table->uuid('uuid')
                ->unique();

            /*
            |--------------------------------------------------------------------------
            | Login Identity
            |--------------------------------------------------------------------------
            |
            | Username is optional because some applications may use only
            | email or phone for authentication.
            |
            */

            $table->string('username', 100)
                ->nullable()
                ->unique();

            /*
            |--------------------------------------------------------------------------
            | Email
            |--------------------------------------------------------------------------
            |
            | email_encrypted:
            |     Actual email address stored encrypted.
            |
            | email_hash:
            |     Blind index / HMAC used for exact email lookup.
            |
            | email_verified_at:
            |     Timestamp when the email address was successfully verified.
            |     NULL means the email address has not been verified.
            |
            */
            $table->text('email_encrypted')
                ->nullable();

            $table->char('email_hash', 64)
                ->nullable()
                ->unique();

            $table->timestamp('email_verified_at')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | Phone
            |--------------------------------------------------------------------------
            |
            | phone_encrypted:
            |     Actual phone number stored encrypted.
            |
            | phone_hash:
            |     Blind index / HMAC used for exact phone lookup.
            |
            | phone_verified_at:
            |     Timestamp when the phone number was successfully verified.
            |     NULL means the phone number has not been verified.
            |
            */
            $table->text('phone_encrypted')
                ->nullable();

            $table->char('phone_hash', 64)
                ->nullable()
                ->unique();

            $table->timestamp('phone_verified_at')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | Authentication
            |--------------------------------------------------------------------------
            */

            $table->string('password');

            /*
            |--------------------------------------------------------------------------
            | Account Status
            |--------------------------------------------------------------------------
            |
            | Examples:
            |
            | active
            | inactive
            | pending
            | suspended
            | blocked
            | locked
            |
            */

            $table->string('status', 30)
                ->default('active')
                ->index();


            /*
            |--------------------------------------------------------------------------
            | Authentication Activity
            |--------------------------------------------------------------------------
            |
            | Only the latest login is kept here for quick access.
            | Complete login history belongs in login_histories.
            |
            */

            $table->timestamp('last_login_at')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | Laravel Authentication
            |--------------------------------------------------------------------------
            */

            $table->rememberToken();

            /*
            |--------------------------------------------------------------------------
            | Timestamps
            |--------------------------------------------------------------------------
            */

            $table->timestamps();

            /*
            |--------------------------------------------------------------------------
            | Soft Deletes
            |--------------------------------------------------------------------------
            |
            | Allows users to be logically removed while retaining their
            | related business/history records.
            |
            */

            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
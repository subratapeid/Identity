<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_profiles', function (Blueprint $table) {

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
            */

            $table->foreignId('user_id')
                ->unique()
                ->constrained('users')
                ->cascadeOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Personal Name
            |--------------------------------------------------------------------------
            |
            | Actual values are encrypted.
            |
            | Searching will be handled through the centralized
            | user_search_indexes table.
            |
            */

            $table->text('first_name_encrypted')
                ->nullable();

            $table->text('middle_name_encrypted')
                ->nullable();

            $table->text('last_name_encrypted')
                ->nullable();

            $table->text('display_name_encrypted')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | Date of Birth
            |--------------------------------------------------------------------------
            |
            | Stored encrypted because it is personal information.
            |
            */

            $table->text('date_of_birth_encrypted')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | Gender
            |--------------------------------------------------------------------------
            |
            | Keep this flexible instead of using a database ENUM.
            | This avoids schema changes if the application later needs
            | additional values.
            |
            */

            $table->string('gender', 30)
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | Bio / About
            |--------------------------------------------------------------------------
            |
            | Optional profile description.
            |
            | It can remain nullable and can be encrypted at the
            | application layer if the application requires it.
            |
            */

            $table->text('bio')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | Profile Completion
            |--------------------------------------------------------------------------
            |
            | Useful for onboarding/profile completion flows.
            |
            */

            $table->unsignedTinyInteger('profile_completion')
                ->default(0);

            /*
            |--------------------------------------------------------------------------
            | Timestamps
            |--------------------------------------------------------------------------
            */

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_profiles');
    }
};
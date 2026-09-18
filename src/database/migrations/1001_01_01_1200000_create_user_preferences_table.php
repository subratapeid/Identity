<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_preferences', function (Blueprint $table) {

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
            | One preference record per user.
            |
            */

            $table->foreignId('user_id')
                ->unique()
                ->constrained('users')
                ->cascadeOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Interface Preferences
            |--------------------------------------------------------------------------
            */

            $table->string('language', 10)
                ->default('en')
                ->index();

            $table->string('timezone', 100)
                ->default('Asia/Kolkata');

            $table->string('date_format', 30)
                ->default('d M Y');

            $table->string('time_format', 20)
                ->default('h:i A');

            /*
            |--------------------------------------------------------------------------
            | Theme / Appearance
            |--------------------------------------------------------------------------
            |
            | Examples:
            |
            | system
            | light
            | dark
            |
            */

            $table->string('theme', 30)
                ->default('system');

            /*
            |--------------------------------------------------------------------------
            | Notification Preferences
            |--------------------------------------------------------------------------
            */

            $table->boolean('email_notifications')
                ->default(true);

            $table->boolean('sms_notifications')
                ->default(true);

            $table->boolean('push_notifications')
                ->default(true);

            /*
            |--------------------------------------------------------------------------
            | Marketing Preferences
            |--------------------------------------------------------------------------
            */

            $table->boolean('marketing_emails')
                ->default(false);

            $table->boolean('marketing_sms')
                ->default(false);

            $table->boolean('marketing_push')
                ->default(false);

            /*
            |--------------------------------------------------------------------------
            | Accessibility
            |--------------------------------------------------------------------------
            */

            $table->boolean('reduced_motion')
                ->default(false);

            $table->boolean('high_contrast')
                ->default(false);

            /*
            |--------------------------------------------------------------------------
            | Dashboard Preferences
            |--------------------------------------------------------------------------
            |
            | Useful for storing things such as:
            |
            | - Sidebar state
            | - Dashboard layout
            | - Visible widgets
            | - Table column preferences
            | - Default filters
            |
            */

            $table->json('dashboard_preferences')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | Application Preferences
            |--------------------------------------------------------------------------
            |
            | Keep application-specific UI preferences here instead of
            | repeatedly changing the database schema.
            |
            */

            $table->json('preferences')
                ->nullable();

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
        Schema::dropIfExists('user_preferences');
    }
};
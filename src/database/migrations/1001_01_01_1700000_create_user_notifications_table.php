<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('user_notifications', function (Blueprint $table) {

            /*
            |--------------------------------------------------------------------------
            | Primary Key
            |--------------------------------------------------------------------------
            */

            $table->id();

            /*
            |--------------------------------------------------------------------------
            | Public Notification Identity
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
            | Notification Type
            |--------------------------------------------------------------------------
            |
            | Examples:
            |
            | order_created
            | payment_received
            | password_changed
            | security_alert
            | system_announcement
            |
            */

            $table->string('type', 100)
                ->index();

            /*
            |--------------------------------------------------------------------------
            | Notification Channel
            |--------------------------------------------------------------------------
            |
            | This represents where the notification is intended to appear.
            |
            | Examples:
            |
            | in_app
            | email
            | sms
            | push
            |
            */

            $table->string('channel', 30)
                ->default('in_app')
                ->index();

            /*
            |--------------------------------------------------------------------------
            | Content
            |--------------------------------------------------------------------------
            |
            | title:
            | Short notification heading.
            |
            | body:
            | Notification message.
            |
            */

            $table->string('title', 255);

            $table->text('body')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | Notification Icon
            |--------------------------------------------------------------------------
            |
            | Store a logical icon name rather than an image/file path.
            |
            */

            $table->string('icon', 100)
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | Priority
            |--------------------------------------------------------------------------
            */

            $table->string('priority', 20)
                ->default('normal')
                ->index();

            /*
            | Examples:
            |
            | low
            | normal
            | high
            | critical
            */

            /*
            |--------------------------------------------------------------------------
            | Related Resource
            |--------------------------------------------------------------------------
            |
            | Useful for notifications such as:
            |
            | "Your order has been shipped"
            |
            | resource_type = Order
            | resource_uuid = ...
            |
            | UUID is used so internal database IDs do not need to be exposed.
            |
            */

            $table->string('resource_type', 100)
                ->nullable()
                ->index();

            $table->uuid('resource_uuid')
                ->nullable()
                ->index();

            /*
            |--------------------------------------------------------------------------
            | Action
            |--------------------------------------------------------------------------
            |
            | Optional action the user can take from the notification.
            |
            | Example:
            |
            | /orders/abc-uuid
            |
            */

            $table->string('action_url', 1000)
                ->nullable();

            $table->string('action_label', 100)
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | Read State
            |--------------------------------------------------------------------------
            */

            $table->boolean('is_read')
                ->default(false)
                ->index();

            $table->timestamp('read_at')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | Archive State
            |--------------------------------------------------------------------------
            */

            $table->boolean('is_archived')
                ->default(false)
                ->index();

            $table->timestamp('archived_at')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | Expiration
            |--------------------------------------------------------------------------
            |
            | Some notifications should disappear after a certain period.
            |
            */

            $table->timestamp('expires_at')
                ->nullable()
                ->index();

            /*
            |--------------------------------------------------------------------------
            | Additional Data
            |--------------------------------------------------------------------------
            |
            | Dynamic notification data can be stored here.
            |
            | Example:
            |
            | {
            |     "order_number": "ORD-2026-00125",
            |     "amount": 2500
            | }
            |
            | Do not store passwords, tokens or other authentication secrets.
            |
            */

            $table->json('data')
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
                'is_read',
                'created_at',
            ]);

            $table->index([
                'user_id',
                'is_archived',
                'created_at',
            ]);

            $table->index([
                'user_id',
                'type',
                'created_at',
            ]);

            $table->index([
                'user_id',
                'expires_at',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_notifications');
    }
};
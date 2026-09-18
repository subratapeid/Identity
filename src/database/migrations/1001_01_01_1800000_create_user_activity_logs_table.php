<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_activity_logs', function (Blueprint $table) {

            /*
            |--------------------------------------------------------------------------
            | Primary Key
            |--------------------------------------------------------------------------
            */

            $table->id();

            /*
            |--------------------------------------------------------------------------
            | Public Activity Identity
            |--------------------------------------------------------------------------
            */

            $table->uuid('uuid')
                ->unique();

            /*
            |--------------------------------------------------------------------------
            | User
            |--------------------------------------------------------------------------
            |
            | Nullable because some system/background actions may not have
            | a directly authenticated user.
            |
            */

            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Activity
            |--------------------------------------------------------------------------
            |
            | Examples:
            |
            | created
            | updated
            | deleted
            | restored
            | viewed
            | downloaded
            | exported
            | imported
            | approved
            | rejected
            | submitted
            | cancelled
            |
            */

            $table->string('action', 50)
                ->index();

            /*
            |--------------------------------------------------------------------------
            | Activity Category
            |--------------------------------------------------------------------------
            |
            | Examples:
            |
            | authentication
            | profile
            | security
            | order
            | invoice
            | payment
            | settings
            | system
            |
            */

            $table->string('category', 50)
                ->nullable()
                ->index();

            /*
            |--------------------------------------------------------------------------
            | Related Resource
            |--------------------------------------------------------------------------
            |
            | Example:
            |
            | resource_type = Invoice
            | resource_uuid = ...
            |
            | This allows the activity log to work across all modules
            | without creating foreign keys to every application's table.
            |
            */

            $table->string('resource_type', 150)
                ->nullable()
                ->index();

            $table->uuid('resource_uuid')
                ->nullable()
                ->index();

            /*
            |--------------------------------------------------------------------------
            | Parent / Context Resource
            |--------------------------------------------------------------------------
            |
            | Useful when an action belongs to another resource.
            |
            | Example:
            |
            | Invoice Line
            |   belongs to Invoice
            |
            */

            $table->string('parent_resource_type', 150)
                ->nullable();

            $table->uuid('parent_resource_uuid')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | Description
            |--------------------------------------------------------------------------
            |
            | Human-readable description for the admin/audit interface.
            |
            */

            $table->string('description', 500)
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | Before / After Data
            |--------------------------------------------------------------------------
            |
            | Useful for audit purposes.
            |
            | Example:
            |
            | before:
            | {
            |     "status": "pending"
            | }
            |
            | after:
            | {
            |     "status": "approved"
            | }
            |
            | IMPORTANT:
            | Sensitive fields should be removed before storing these values.
            |
            */

            $table->json('old_values')
                ->nullable();

            $table->json('new_values')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | Request Information
            |--------------------------------------------------------------------------
            */

            $table->ipAddress('ip_address')
                ->nullable();

            $table->text('user_agent')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | Request / Correlation ID
            |--------------------------------------------------------------------------
            |
            | Useful for tracing one request across modules/services.
            |
            */

            $table->string('request_id', 100)
                ->nullable()
                ->index();

            $table->string('correlation_id', 100)
                ->nullable()
                ->index();

            /*
            |--------------------------------------------------------------------------
            | Session / Device
            |--------------------------------------------------------------------------
            */

            $table->uuid('session_uuid')
                ->nullable()
                ->index();

            $table->uuid('device_uuid')
                ->nullable()
                ->index();

            /*
            |--------------------------------------------------------------------------
            | Source
            |--------------------------------------------------------------------------
            |
            | Examples:
            |
            | web
            | api
            | mobile
            | admin
            | system
            | console
            |
            */

            $table->string('source', 30)
                ->nullable()
                ->index();

            /*
            |--------------------------------------------------------------------------
            | Result
            |--------------------------------------------------------------------------
            */

            $table->string('status', 20)
                ->default('success')
                ->index();

            /*
            | Examples:
            |
            | success
            | failed
            | denied
            */

            $table->string('failure_reason', 255)
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | Additional Metadata
            |--------------------------------------------------------------------------
            |
            | Keep only non-sensitive contextual information.
            |
            | Never store:
            | - Passwords
            | - Access tokens
            | - Refresh tokens
            | - API secrets
            | - OTPs
            |
            */

            $table->json('metadata')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | Timestamp
            |--------------------------------------------------------------------------
            |
            | Activity logs are append-only, so updated_at is intentionally
            | not included.
            |
            */

            $table->timestamp('occurred_at')
                ->useCurrent()
                ->index();

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
                'category',
                'occurred_at',
            ]);

            $table->index([
                'user_id',
                'action',
                'occurred_at',
            ]);

            $table->index([
                'resource_type',
                'resource_uuid',
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
        Schema::dropIfExists('user_activity_logs');
    }
};
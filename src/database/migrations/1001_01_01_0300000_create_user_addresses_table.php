<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('user_addresses', function (Blueprint $table) {

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
                ->constrained('users')
                ->cascadeOnDelete();

            /*
             |--------------------------------------------------------------------------
             | Address Information
             |--------------------------------------------------------------------------
             */

            $table->string('label', 100)->nullable();          // Head Office, Home, Warehouse
            $table->string('address_type', 50);                // billing, shipping, office, home...

            $table->boolean('is_default_billing')->default(false);
            $table->boolean('is_default_shipping')->default(false);

            /*
            |--------------------------------------------------------------------------
            | Contact Information
            |--------------------------------------------------------------------------
            */

            $table->string('contact_person', 150)->nullable();

            $table->string('phone_encrypted', 30)->nullable();
            $table->string('alternate_phone_encrypted', 30)->nullable();

            $table->string('email_encrypted')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Address
            |--------------------------------------------------------------------------
            */

            $table->string('address_line_1_encrypted', 255);

            $table->string('address_line_2', 255)->nullable();

            $table->string('landmark', 255)->nullable();

            $table->string('area', 150)->nullable();

            $table->string('city', 150);

            $table->string('district', 150)->nullable();

            $table->string('state', 150)->nullable();

            $table->string('state_code', 20)->nullable();

            $table->string('country', 150)->default('India');

            $table->string('country_code', 2)->default('IN');

            $table->string('postal_code', 20);

            /*
            |--------------------------------------------------------------------------
            | Location
            |--------------------------------------------------------------------------
            */

            $table->decimal('latitude', 10, 7)->nullable();

            $table->decimal('longitude', 10, 7)->nullable();

            /*
            |--------------------------------------------------------------------------
            | Internal
            |--------------------------------------------------------------------------
            */

            $table->text('delivery_instructions')->nullable();

            $table->boolean('is_active')->default(true);

            $table->uuid('created_by_user_uuid')->nullable();

            $table->uuid('updated_by_user_uuid')->nullable();

            $table->timestamps();

            $table->softDeletes();


            /*
            |--------------------------------------------------------------------------
            | Indexes
            |--------------------------------------------------------------------------
            */

            $table->index([
                'user_id',
                'address_type',
                'is_active',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_addresses');
    }
};
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('user_search_indexes', function (Blueprint $table) {

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
            | Searchable Entity
            |--------------------------------------------------------------------------
            |
            | Examples:
            |
            | email
            | phone
            | first_name
            | middle_name
            | last_name
            | display_name
            | address
            | city
            | postal_code
            |
            */

            $table->string('field', 50)
                ->index();

            /*
            |--------------------------------------------------------------------------
            | Search Token
            |--------------------------------------------------------------------------
            |
            | This contains a keyed HMAC/hash of the normalized search token.
            |
            | Example:
            |
            | "subrata"
            |     ↓
            | normalize
            |     ↓
            | HMAC-SHA256
            |     ↓
            | token_hash
            |
            | The original searchable value is NOT stored.
            |
            */

            $table->char('token_hash', 64);

            /*
            |--------------------------------------------------------------------------
            | Token Type
            |--------------------------------------------------------------------------
            |
            | exact:
            |     Used for exact matching.
            |
            | prefix:
            |     Used for partial/prefix searching.
            |
            | For example:
            |
            | "subrata"
            | "sub"
            | "subr"
            | "subra"
            |
            */

            $table->string('token_type', 20)
                ->default('exact')
                ->index();

            /*
            |--------------------------------------------------------------------------
            | Token Position
            |--------------------------------------------------------------------------
            |
            | Useful when generating multiple tokens from a value.
            |
            | Example:
            |
            | "subrata kumar"
            |
            | position 0 = subrata
            | position 1 = kumar
            |
            */

            $table->unsignedSmallInteger('position')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | Normalization Version
            |--------------------------------------------------------------------------
            |
            | Allows the search algorithm to change in the future without
            | making existing records impossible to understand.
            |
            | Example:
            |
            | 1 = current normalization algorithm
            | 2 = future algorithm
            |
            */

            $table->unsignedTinyInteger('version')
                ->default(1);

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

            $table->unique(
                [
                    'user_id',
                    'field',
                    'token_hash',
                    'token_type',
                    'version',
                ],
                'usi_user_token_unique'
            );

            $table->index([
                'field',
                'token_hash',
            ]);

            $table->index([
                'field',
                'token_hash',
                'token_type',
            ]);

            $table->index([
                'user_id',
                'field',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_search_indexes');
    }
};
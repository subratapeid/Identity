<?php

use Illuminate\Support\Facades\Route;

Route::prefix('api/identity')
    ->name('identity.')
    ->group(function () {

        Route::get(
            '/users',
            function () {
                return response()->json([
                    'package' => config('identity.name'),
                    'version' => config('identity.version'),
                    'status' => 'Identity package loaded successfully',
                ]);
            }
        );

    });
<?php

use Illuminate\Support\Facades\Route;

Route::middleware('web')
    ->prefix('identity')
    ->group(function () {

        Route::get('/', function () {
            return response()->json([
                'package' => config('identity.name'),
                'version' => config('identity.version'),
                'status' => 'Identity package loaded successfully',
            ]);
        });

    });
<?php

declare(strict_types=1);

namespace Pagelyne\Identity\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;

class IdentityServiceProvider extends ServiceProvider
{
    public function register(): void
    {

        $configPath = __DIR__ . '/../Config';

        if (!is_dir($configPath)) {
            return;
        }

        foreach (File::files($configPath) as $file) {
            $name = pathinfo(
                $file->getFilename(),
                PATHINFO_FILENAME
            );

            if ($name === 'permission') {
                $this->mergeConfigFrom(
                    $file->getRealPath(),
                    'permission'
                );

                continue;
            }

            $this->mergeConfigFrom(
                $file->getRealPath(),
                $name
            );
        }
    }

    public function boot(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Identity Authentication
        |--------------------------------------------------------------------------
        */

        config([
            'auth' => require __DIR__ . '/../Config/auth.php',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Migrations
        |--------------------------------------------------------------------------
        */

        $this->loadMigrationsFrom(
            __DIR__ . '/../Database/Migrations'
        );

        /*
        |--------------------------------------------------------------------------
        | Routes
        |--------------------------------------------------------------------------
        */

        $this->loadRoutesFrom(
            __DIR__ . '/../routes/web.php'
        );

        $this->loadRoutesFrom(
            __DIR__ . '/../routes/api.php'
        );
        /*
        |--------------------------------------------------------------------------
        | Views
        |--------------------------------------------------------------------------
        */

        $viewsPath = __DIR__ . '/../Resources/Views';

        if (is_dir($viewsPath)) {
            $this->loadViewsFrom(
                $viewsPath,
                'identity'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Anonymous Blade Components
        |--------------------------------------------------------------------------
        */

        $componentsPath = $viewsPath . '/components';

        if (is_dir($componentsPath)) {
            Blade::anonymousComponentPath(
                $componentsPath,
                'identity'
            );
        }
    }
}
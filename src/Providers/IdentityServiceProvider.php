<?php

declare(strict_types=1);

namespace Pagelyne\Identity\Providers;

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;
use Pagelyne\Identity\Http\Middleware\Authenticate;

class IdentityServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->registerConfigs();
    }

    public function boot(): void
    {
        $this->registerMigrations();
        $this->registerViews();
        $this->registerComponents();
        $this->registerMiddleware();
    }

    protected function registerConfigs(): void
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

            if ($name === 'auth') {
                $this->mergeConfigFrom(
                    $file->getRealPath(),
                    'auth'
                );
                continue;
            }

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

    protected function registerMigrations(): void
    {
        $this->loadMigrationsFrom(
            __DIR__ . '/../Database/Migrations'
        );
    }

    protected function registerViews(): void
    {
        $viewsPath = __DIR__ . '/../Resources/Views';

        if (!is_dir($viewsPath)) {
            return;
        }

        $this->loadViewsFrom(
            $viewsPath,
            'identity'
        );
    }

    protected function registerComponents(): void
    {
        $componentsPath = __DIR__ . '/../Resources/Views/components';

        if (!is_dir($componentsPath)) {
            return;
        }

        Blade::anonymousComponentPath(
            $componentsPath,
            'identity'
        );
    }

    protected function registerMiddleware(): void
    {
        $this->app
            ->make(Router::class)
            ->aliasMiddleware(
                'identity.auth',
                Authenticate::class
            );
    }
}
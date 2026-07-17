<?php

namespace Zerp\Hrm\Providers;

use Illuminate\Support\ServiceProvider;

class HrmServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $routesPath = __DIR__.'/../Routes/web.php';
        if (file_exists($routesPath)) {
            $this->loadRoutesFrom($routesPath);
        }
        
        $apiRoutesPath = __DIR__.'/../Routes/api.php';
        if (file_exists($apiRoutesPath)) {
            $this->loadRoutesFrom($apiRoutesPath);
        }

        // Scoped Swagger/OpenAPI docs for this module at /docs/hrm.
        // Guarded so the package still works if the host app has no Scramble.
        if (class_exists(\Dedoc\Scramble\Scramble::class)) {
            \Dedoc\Scramble\Scramble::registerApi('hrm', [
                'api_path' => 'api/hrm',
                'info' => ['version' => \Composer\InstalledVersions::getPrettyVersion('zerp/hrm') ?? '1.0.0', 'description' => 'Zerp HRM module REST API for mobile and third-party clients.'],
                'ui' => ['title' => 'Zerp HRM API'],
            ])->expose(ui: '/docs/hrm', document: '/docs/hrm.json');
        }

        $migrationsPath = __DIR__.'/../Database/Migrations';
        if (is_dir($migrationsPath)) {
            $this->loadMigrationsFrom($migrationsPath);
        }
    }

    public function register(): void
    {
        $this->app->register(EventServiceProvider::class);
    }
}
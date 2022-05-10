<?php
namespace App\Providers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
    }

    /**
     * Register any application services.
     */
    public function register()
    {
        $this->registerStorage();
    }

    protected function registerStorage()
    {
        $dir = getenv('HOME').'/.config/spirit';

        if (! is_dir($dir)) {
            throw_unless(mkdir($dir, recursive: true), "Unable to create storage directory: '{$dir}'.");
        }

        $this->app->bind('storage-dir', $dir);
        $this->app->get('config')->set('cache.stores.file.path', "{$dir}/cache");
    }
}

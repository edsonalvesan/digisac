<?php

namespace EdsonAlvesan\DigiSac\Laravel;

use EdsonAlvesan\DigiSac\Api;
use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Container\Container as Application;
use Laravel\Lumen\Application as LumenApplication;
use Illuminate\Foundation\Application as LaravelApplication;

/**
 * Class DigiSacServiceProvider.
 */
class DigiSacServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Holds path to Config File.
     *
     * @var string
     */
    protected $config_filepath;

    /**
     * Bootstrap the application events.
     */
    public function boot()
    {
        $this->setupConfig($this->app);
    }
    
    /**
     * Setup the config.
     *
     * @param \Illuminate\Contracts\Container\Container $app
     *
     * @return void
     */
    protected function setupConfig(Application $app)
    {
        $source = __DIR__.'/config/digisac.php';

        if ($app instanceof LaravelApplication && $app->runningInConsole()) {
            $this->publishes([$source => config_path('digisac.php')]);
        } elseif ($app instanceof LumenApplication) {
            $app->configure('digisac');
        }

        $this->mergeConfigFrom($source, 'digisac');
    }
    
    /**
     * Register the service provider.
     */
    public function register()
    {
        $this->registerDigiSac($this->app);
    }

    /**
     * Initialize DigiSac Bot SDK Library with Default Config.
     *
     * @param Application $app
     */
    protected function registerDigiSac(Application $app)
    {
        $app->singleton(Api::class, function ($app) {
            $config = $app['config'];

            $digisac = new Api(
                $config->get('telegram.bot_token', false),
                $config->get('telegram.async_requests', false),
                $config->get('telegram.http_client_handler', null)
            );

            // Register Commands
            $digisac->addCommands($config->get('telegram.commands', []));

            // Check if DI needs to be enabled for Commands
            if ($config->get('telegram.inject_command_dependencies', false)) {
                $digisac->setContainer($app);
            }

            return $digisac;
        });

        $app->alias(Api::class, 'digisac');
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['digisac', Api::class];
    }
}

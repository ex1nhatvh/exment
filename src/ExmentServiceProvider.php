<?php

namespace Exceedone\Exment;

use Storage;
use Request;
use Encore\Admin\Admin;
use Exceedone\Exment\Providers as ExmentProviders;
use Exceedone\Exment\Services\Plugin\PluginInstaller;
use Exceedone\Exment\Adapter\AdminLocal;
use Exceedone\Exment\Model\Plugin;
use Exceedone\Exment\Enums\PluginType;
use Exceedone\Exment\Validator\UniqueInTableValidator;
use Exceedone\Exment\Middleware\Initialize;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use League\Flysystem\Filesystem;
use Illuminate\Support\Facades\File;
use Illuminate\Console\Scheduling\Schedule;

class ExmentServiceProvider extends ServiceProvider
{
    /**
     * Application Policy Map
     *
     * @var array
     */
    protected $policies = [
        'Exceedone\Exment\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * @var array commands
     */
    protected $commands = [
        'Exceedone\Exment\Console\InstallCommand',
        'Exceedone\Exment\Console\ScheduleCommand',
        'Exceedone\Exment\Console\BackupCommand',
        'Exceedone\Exment\Console\RestoreCommand',
    ];

    /**
     * The application's route middleware.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'admin.auth'       => \Exceedone\Exment\Middleware\Authenticate::class,
        'admin.bootstrap2'  => \Exceedone\Exment\Middleware\Bootstrap::class,
        'admin.initialize'  => \Exceedone\Exment\Middleware\Initialize::class,
        'admin.morph'  => \Exceedone\Exment\Middleware\Morph::class,
        'adminapi.auth'       => \Exceedone\Exment\Middleware\AuthenticateApi::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'admin' => [
            'admin.auth',
            'admin.pjax',
            'admin.log',
            'admin.bootstrap',
            'admin.permission',
            'admin.bootstrap2',
            'admin.initialize',
            'admin.morph',
        ],
        'admin_anonymous' => [
            'admin.pjax',
            'admin.log',
            'admin.bootstrap',
            'admin.permission',
            'admin.bootstrap2',
            'admin.initialize',
            'admin.morph',
        ],
        'adminapi' => [
            'adminapi.auth',
            'throttle:60,1',
            'bindings',
        ]
    ];

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->bootApp();
        $this->bootSetting();

        $this->publish();
        $this->load();

        $this->registerPolicies();

        $this->bootPlugin();
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        require_once(__DIR__.'/Services/Helpers.php');

        // register route middleware.
        foreach ($this->routeMiddleware as $key => $middleware) {
            app('router')->aliasMiddleware($key, $middleware);
        }

        // register middleware group.
        foreach ($this->middlewareGroups as $key => $middleware) {
            app('router')->middlewareGroup($key, $middleware);
        }
    }

    protected function publish(){
        $this->mergeConfigFrom(
            __DIR__.'/../config/exment.php',
            'exment'
        );
        
        $this->publishes([__DIR__.'/../config' => config_path()]);
        $this->publishes([__DIR__.'/../resources/lang_vendor' => resource_path('lang')], 'lang');
        $this->publishes([__DIR__.'/../public' => public_path('')], 'public');
        $this->publishes([__DIR__.'/../resources/views/vendor' => resource_path('views/vendor')], 'views_vendor');
    }

    protected function load(){
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'exment');
        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'exment');
    }

    protected function bootApp(){
        $this->app->register(ExmentProviders\RouteServiceProvider::class);
        $this->app->register(ExmentProviders\RouteOAuthServiceProvider::class);
        $this->app->register(ExmentProviders\PasswordResetServiceProvider::class);
        
        $this->commands($this->commands);

        $this->app->booted(function () {
            $schedule = $this->app->make(Schedule::class);
            $schedule->command('exment:schedule')->hourly();
        });
    }

    // plugin --------------------------------------------------
    
    /**
     * Check URI after '/admin/' then get plugin satisfying conditions and execute this plugin
     */
    protected function bootPlugin()
    {
        $pattern = '@plugins/([^/\?]+)@';
        preg_match($pattern, Request::url(), $matches);

        if (!isset($matches) || count($matches) <= 1) {
            return;
        }
        $pluginName = $matches[1];
        
        $plugin = $this->getPluginActivate($pluginName);
        if (!isset($plugin)) {
            return;
        }
        $base_path = path_join(app_path(), 'plugins', $plugin->plugin_name);
        if (! $this->app->routesAreCached()) {
            $config_path = path_join($base_path, 'config.json');
            if (file_exists($config_path)) {
                $json = json_decode(File::get($config_path), true);
                PluginInstaller::route($plugin, $json);
            }
        }
        $this->loadViewsFrom(path_join($base_path, 'views'), $plugin->plugin_name);
    }

    /**
     * Check plugin satisfying conditions
     */
    protected function getPluginActivate($pluginName)
    {
        $plugin = Plugin
            ::where('active_flg', 1)
            ->where('plugin_type', PluginType::PAGE)
            ->where('options->uri', $pluginName)
            ->first();

        if ($plugin !== null) {
            return $plugin;
        }

        return false;
    }

    protected function bootSetting()
    {
        // Extend --------------------------------------------------
        Auth::provider('exment-auth', function ($app, array $config) {
            // Return an instance of Illuminate\Contracts\Auth\UserProvider...
            return new Providers\CustomUserProvider($app['hash'], \Exceedone\Exment\Model\LoginUser::class);
        });
        
        \Validator::resolver(function ($translator, $data, $rules, $messages) {
            return new UniqueInTableValidator($translator, $data, $rules, $messages);
        });

        Storage::extend('admin-local', function ($app, $config) {
            return new Filesystem(new AdminLocal(array_get($config, 'root')));
        });

        Initialize::initializeConfig(false);
        
        Admin::booting(function(){
            Initialize::initializeFormField();
        });
    }
}

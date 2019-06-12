<?php

namespace XADMIN\LaravelCmf;

use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Routing\Router;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Intervention\Image\ImageServiceProvider;
use Larapack\DoctrineSupport\DoctrineSupportServiceProvider;
use XADMIN\LaravelCmf\LaravelCmfHooksServiceProvider;
use XADMIN\LaravelCmf\Events\FormFieldsRegistered;
use XADMIN\LaravelCmf\Facades\LaravelCmf as LaravelCmfFacade;
use XADMIN\LaravelCmf\FormFields\After\DescriptionHandler;
use XADMIN\LaravelCmf\Http\Middleware\LaravelCmfAdminMiddleware;
use XADMIN\LaravelCmf\Models\MenuItem;
use XADMIN\LaravelCmf\Models\Setting;
use XADMIN\LaravelCmf\Policies\BasePolicy;
use XADMIN\LaravelCmf\Policies\MenuItemPolicy;
use XADMIN\LaravelCmf\Policies\SettingPolicy;
use XADMIN\LaravelCmf\Providers\LaravelCmfDummyServiceProvider;
use XADMIN\LaravelCmf\Providers\LaravelCmfEventServiceProvider;
use XADMIN\LaravelCmf\Translator\Collection as TranslatorCollection;

class LaravelCmfServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Setting::class  => SettingPolicy::class,
        MenuItem::class => MenuItemPolicy::class,
    ];

    protected $gates = [
        'browse_admin',
        'browse_bread',
        'browse_database',
        'browse_media',
        'browse_compass',
        'browse_hooks',
    ];

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->app->register(LaravelCmfEventServiceProvider::class);
        $this->app->register(ImageServiceProvider::class);
        $this->app->register(LaravelCmfDummyServiceProvider::class);
        $this->app->register(LaravelCmfHooksServiceProvider::class);
        $this->app->register(DoctrineSupportServiceProvider::class);

        $loader = AliasLoader::getInstance();
        $loader->alias('LaravelCmf', LaravelCmfFacade::class);

        $this->app->singleton('laravel-cmf', function () {
            return new LaravelCmf();
        });

        $this->app->singleton('LaravelCmfAuth', function () {
            return auth();
        });

        $this->loadHelpers();

        $this->registerAlertComponents();
        $this->registerFormFields();

        $this->registerConfigs();

        if ($this->app->runningInConsole()) {
            $this->registerPublishableResources();
            $this->registerConsoleCommands();
        }

        if (!$this->app->runningInConsole() || config('app.env') == 'testing') {
            $this->registerAppCommands();
        }
    }

    /**
     * Bootstrap the application services.
     *
     * @param \Illuminate\Routing\Router $router
     */
    public function boot(Router $router, Dispatcher $event)
    {
        if (config('laravel-cmf.user.add_default_role_on_register')) {
            $app_user = config('laravel-cmf.user.namespace') ?: config('auth.providers.users.model');
            $app_user::created(function ($user) {
                if (is_null($user->role_id)) {
                    LaravelCmfFacade::model('User')->findOrFail($user->id)
                        ->setRole(config('laravel-cmf.user.default_role'))
                        ->save();
                }
            });
        }

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'laravel-cmf');

        $router->aliasMiddleware('admin.user', LaravelCmfAdminMiddleware::class);

        $this->loadTranslationsFrom(realpath(__DIR__.'/../publishable/lang'), 'laravel-cmf');

        if (config('laravel-cmf.database.autoload_migrations', true)) {
            if (config('app.env') == 'testing') {
                $this->loadMigrationsFrom(realpath(__DIR__.'/migrations'));
            }

            $this->loadMigrationsFrom(realpath(__DIR__.'/../migrations'));
        }

        $this->loadAuth();

        $this->registerViewComposers();

        $event->listen('laravel-cmf.alerts.collecting', function () {
            $this->addStorageSymlinkAlert();
        });

        $this->bootTranslatorCollectionMacros();
    }

    /**
     * Load helpers.
     */
    protected function loadHelpers()
    {
        foreach (glob(__DIR__.'/Helpers/*.php') as $filename) {
            require_once $filename;
        }
    }

    /**
     * Register view composers.
     */
    protected function registerViewComposers()
    {
        // Register alerts
        View::composer('laravel-cmf::*', function ($view) {
            $view->with('alerts', LaravelCmfFacade::alerts());
        });
    }

    /**
     * Add storage symlink alert.
     */
    protected function addStorageSymlinkAlert()
    {
        if (app('router')->current() !== null) {
            $currentRouteAction = app('router')->current()->getAction();
        } else {
            $currentRouteAction = null;
        }
        $routeName = is_array($currentRouteAction) ? Arr::get($currentRouteAction, 'as') : null;

        if ($routeName != 'laravel-cmf.dashboard') {
            return;
        }

        $storage_disk = (!empty(config('laravel-cmf.storage.disk'))) ? config('laravel-cmf.storage.disk') : 'public';

        if (request()->has('fix-missing-storage-symlink')) {
            if (file_exists(public_path('storage'))) {
                if (@readlink(public_path('storage')) == public_path('storage')) {
                    rename(public_path('storage'), 'storage_old');
                }
            }

            if (!file_exists(public_path('storage'))) {
                $this->fixMissingStorageSymlink();
            }
        } elseif ($storage_disk == 'public') {
            if (!file_exists(public_path('storage')) || @readlink(public_path('storage')) == public_path('storage')) {
                $alert = (new Alert('missing-storage-symlink', 'warning'))
                    ->title(__('laravel-cmf::error.symlink_missing_title'))
                    ->text(__('laravel-cmf::error.symlink_missing_text'))
                    ->button(__('laravel-cmf::error.symlink_missing_button'), '?fix-missing-storage-symlink=1');
                LaravelCmfFacade::addAlert($alert);
            }
        }
    }

    protected function fixMissingStorageSymlink()
    {
        app('files')->link(storage_path('app/public'), public_path('storage'));

        if (file_exists(public_path('storage'))) {
            $alert = (new Alert('fixed-missing-storage-symlink', 'success'))
                ->title(__('laravel-cmf::error.symlink_created_title'))
                ->text(__('laravel-cmf::error.symlink_created_text'));
        } else {
            $alert = (new Alert('failed-fixing-missing-storage-symlink', 'danger'))
                ->title(__('laravel-cmf::error.symlink_failed_title'))
                ->text(__('laravel-cmf::error.symlink_failed_text'));
        }

        LaravelCmfFacade::addAlert($alert);
    }

    /**
     * Register alert components.
     */
    protected function registerAlertComponents()
    {
        $components = ['title', 'text', 'button'];

        foreach ($components as $component) {
            $class = 'XADMIN\\LaravelCmf\\Alert\\Components\\'.ucfirst(camel_case($component)).'Component';

            $this->app->bind("laravel-cmf.alert.components.{$component}", $class);
        }
    }

    protected function bootTranslatorCollectionMacros()
    {
        Collection::macro('translate', function () {
            $transtors = [];

            foreach ($this->all() as $item) {
                $transtors[] = call_user_func_array([$item, 'translate'], func_get_args());
            }

            return new TranslatorCollection($transtors);
        });
    }

    /**
     * Register the publishable files.
     */
    private function registerPublishableResources()
    {
        $publishablePath = dirname(__DIR__).'/publishable';

        $publishable = [
            'laravel_cmf_avatar' => [
                "{$publishablePath}/dummy_content/users/" => storage_path('app/public/users'),
            ],
            'seeds' => [
                "{$publishablePath}/database/seeds/" => database_path('seeds'),
            ],
            'config' => [
                "{$publishablePath}/config/laravel-cmf.php" => config_path('laravel-cmf.php'),
            ],

        ];

        foreach ($publishable as $group => $paths) {
            $this->publishes($paths, $group);
        }
    }

    public function registerConfigs()
    {
        $this->mergeConfigFrom(
            dirname(__DIR__).'/publishable/config/laravel-cmf.php', 'laravel-cmf'
        );
    }

    public function loadAuth()
    {
        // DataType Policies

        // This try catch is necessary for the Package Auto-discovery
        // otherwise it will throw an error because no database
        // connection has been made yet.
        try {
            if (Schema::hasTable(LaravelCmfFacade::model('DataType')->getTable())) {
                $dataType = LaravelCmfFacade::model('DataType');
                $dataTypes = $dataType->select('policy_name', 'model_name')->get();

                foreach ($dataTypes as $dataType) {
                    $policyClass = BasePolicy::class;
                    if (isset($dataType->policy_name) && $dataType->policy_name !== ''
                        && class_exists($dataType->policy_name)) {
                        $policyClass = $dataType->policy_name;
                    }

                    $this->policies[$dataType->model_name] = $policyClass;
                }

                $this->registerPolicies();
            }
        } catch (\PDOException $e) {
            Log::error('No Database connection yet in LaravelCmfServiceProvider loadAuth()');
        }

        // Gates
        foreach ($this->gates as $gate) {
            Gate::define($gate, function ($user) use ($gate) {
                return $user->hasPermission($gate);
            });
        }
    }

    protected function registerFormFields()
    {
        $formFields = [
            'checkbox',
            'multiple_checkbox',
            'color',
            'date',
            'file',
            'image',
            'multiple_images',
            'media_picker',
            'number',
            'password',
            'radio_btn',
            'rich_text_box',
            'code_editor',
            'markdown_editor',
            'select_dropdown',
            'select_multiple',
            'text',
            'text_area',
            'time',
            'timestamp',
            'hidden',
            'coordinates',
        ];

        foreach ($formFields as $formField) {
            $class = studly_case("{$formField}_handler");

            LaravelCmfFacade::addFormField("XADMIN\\LaravelCmf\\FormFields\\{$class}");
        }

        LaravelCmfFacade::addAfterFormField(DescriptionHandler::class);

        event(new FormFieldsRegistered($formFields));
    }

    /**
     * Register the commands accessible from the Console.
     */
    private function registerConsoleCommands()
    {
        $this->commands(Commands\InstallCommand::class);
        $this->commands(Commands\ControllersCommand::class);
        $this->commands(Commands\AdminCommand::class);
    }

    /**
     * Register the commands accessible from the App.
     */
    private function registerAppCommands()
    {
        $this->commands(Commands\MakeModelCommand::class);
    }
}

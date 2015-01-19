<?php namespace Layout;

use Illuminate\Support\ServiceProvider;

class LayoutServiceProvider extends ServiceProvider {

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * This service provider is a great spot to register your various container
     * bindings with the application. As you can see, we are registering our
     * "Registrar" implementation here. You can add your own bindings too!
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('Layout\LayoutInterface','Layout\Layout');
        $this->app->bind('Layout\RendererInterface','Layout\Renderer');
        $this->app->bind('Layout\RendererInterface','Layout\Renderer');

        $this->app->singleton('Layout\ConfigInterface', function () {
            $designDir = realpath(base_path('resources/design'));
            
            /** @var \Layout\Config $config */
            $config = $this->app->make('Layout\Config');
            $config->addConfigPath($designDir . '/layout');
            $config->addTemplatePath($designDir . '/template');

            $config->registerConfigFile('main.yaml');
            $config->registerConfigFile('local.yaml');
            
            return $config;
        });

        $this->app->bind('layout.element.simple', 'Layout\Element\Simple');
        $this->app->bind('layout.element.text', 'Layout\Element\Text');
        $this->app->bind('layout.element.template', 'Layout\Element\Template');

        $this->app->when('Layout\Layout')->needs('Illuminate\Contracts\Events\Dispatcher')->give('Illuminate\Events\Dispatcher');
    }

}

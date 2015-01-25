<?php namespace Layout;

use Illuminate\Support\ServiceProvider as ServiceProviderAbstract;
use Layout\Element\Factory\FactoryHtml;
use Layout\Element\Factory\FactoryJson;

class ServiceProvider extends ServiceProviderAbstract 
{
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
        $this->app->when('Layout\Layout')->needs('Illuminate\Contracts\Events\Dispatcher')->give('Illuminate\Events\Dispatcher');
        $this->app->bind('Layout\LayoutInterface','  Layout\Layout');

        $this->initializeConfig();
        $this->initializeElementFactory();
    }
    
    protected function initializeConfig()
    {
        $this->app->singleton('Layout\LayoutConfig', function () {
            $designDir = realpath(base_path('resources/design'));

            /** @var \Layout\LayoutConfig $config */
            $config = new LayoutConfig($this->app->make('Symfony\Component\Yaml\Parser'));
            $config->addConfigPath($designDir . '/layout');
            $config->addTemplatePath($designDir . '/template');

            $config->registerConfigFile('main.yaml');
            $config->registerConfigFile('local.yaml');

            return $config;
        });
    }
    
    protected function initializeElementFactory()
    {
        $this->app->singleton('Layout\Element\Factory\FactoryHtml', function () {
            $factory = new FactoryHtml($this->app);
            $factory->registerDefaultModel('Layout\Element\Type\TypeDefault');
            $factory->registerDefaultOutputModel('Layout\Element\Output\Html\HtmlDefault');

            $factory->register('template', 'Layout\Element\Type\Template', 'Layout\Element\Output\Html\Template');
            $factory->register('welcome', 'Layout\Element\Type\Welcome', 'Layout\Element\Output\Html\Welcome');
            $factory->register('text', 'Layout\Element\Type\Text', 'Layout\Element\Output\Html\Text');
            
            return $factory;
        });

        $this->app->singleton('Layout\Element\Factory\FactoryJson', function () {
            $factory = new FactoryJson($this->app);
            $factory->registerDefaultModel('Layout\Element\Type\TypeDefault');
            $factory->registerDefaultOutputModel('Layout\Element\Output\Json\JsonDefault');
            
            $factory->register('template', 'Layout\Element\Type\Template');
            $factory->register('welcome', 'Layout\Element\Type\Welcome');
            $factory->register('text', 'Layout\Element\Type\Text');
            
            return $factory;
        });
    }
    
}

<?php namespace Layout;

use Illuminate\Support\ServiceProvider;
use Layout\Output\FormatInterface;
use Layout\Output\FormatHtml;
use Layout\Output\FormatJson;

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
        $this->app->when('Layout\Layout')->needs('Illuminate\Contracts\Events\Dispatcher')->give('Illuminate\Events\Dispatcher');
        $this->app->bind('Layout\LayoutInterface','  Layout\Layout');

        $this->initializeConfig();
        $this->initializeElements();
        $this->initializeOutputFormatHtml();
        $this->initializeOutputFormatJson();
    }
    
    protected function initializeConfig()
    {
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
    }
    
    protected function initializeOutputFormatHtml()
    {
        /** @var FormatHtml $format */
        $format = $this->app->make('Layout\Output\FormatHtml');

        $this->registerOutputModel($format, 'default', 'Layout\Element\Output\Html\HtmlDefault');
        $this->registerOutputModel($format, 'template', 'Layout\Element\Output\Html\Template');
        $this->registerOutputModel($format, 'welcome', 'Layout\Element\Output\Html\Welcome');
        $this->registerOutputModel($format, 'text', 'Layout\Element\Output\Html\Text');
        
        $this->app->instance('Layout\Output\FormatHtml', $format);
    }

    protected function initializeOutputFormatJson()
    {
        /** @var FormatJson $format */
        $format = $this->app->make('Layout\Output\FormatJson');

        $this->registerOutputModel($format, 'default', 'Layout\Element\Output\Html\HtmlDefault');
        $this->registerOutputModel($format, 'template', 'Layout\Element\Output\Html\Template');
        $this->registerOutputModel($format, 'welcome', 'Layout\Element\Output\Html\Welcome');
        $this->registerOutputModel($format, 'text', 'Layout\Element\Output\Html\Text');

        $this->app->instance('Layout\Output\FormatJson', $format);
    }
    
    protected function registerOutputModel(FormatInterface $format, $code, $concrete)
    {
        $format->registerOutputModel($code);
        $this->app->bind($format->formatOutputModelAlias($code), $concrete);
    }
    
    protected function initializeElements()
    {
        $this->app->bind('Layout\OutputInterface', function () {
            /** @var Output $output */
            $output = $this->app->make('Layout\Output');

            $this->registerElementTypeModel($output, 'default', 'Layout\Element\Type\ElementDefault');
            $this->registerElementTypeModel($output, 'template', 'Layout\Element\Type\Template');
            $this->registerElementTypeModel($output, 'welcome', 'Layout\Element\Type\Welcome');
            $this->registerElementTypeModel($output, 'text', 'Layout\Element\Type\Text');
            
            return $output;
        });
    }
    
    protected function registerElementTypeModel(Output $output, $code, $concrete)
    {
        $output->registerElementTypeModel($code);
        $this->app->bind($output->getElementIocAlias($code), $concrete);
    }
}

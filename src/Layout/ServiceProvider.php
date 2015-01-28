<?php
/** {license_text}  */
namespace Layout;

use Illuminate\Support\ServiceProvider as ServiceProviderAbstract;
use Layout\Element\Factory\FactoryHtml;
use Layout\Element\Factory\FactoryJson;

class ServiceProvider 
    extends ServiceProviderAbstract 
{
    protected $defer = true;
    
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    public function provides()
    {
        return [
            'Layout\Element\Factory\FactoryHtml',
            'Layout\Element\Factory\FactoryJson',
        ];
    }

    /**
     * Register default element types
     */
    public function register()
    {
        $this->app->singleton('Layout\Element\Factory\FactoryHtml', function () {
            /** @var \Illuminate\Events\Dispatcher $eventDispatcher */
            $eventDispatcher = $this->app->make('Illuminate\Events\Dispatcher');

            $layoutFactory = new FactoryHtml($this->app);
            $layoutFactory->registerDefaultModel('Layout\Element\Type\TypeDefault');
            $layoutFactory->registerDefaultOutputModel('Layout\Element\Output\Html\HtmlDefault');

            $eventDispatcher->fire('layout.initialize.factory.html', array($layoutFactory));

            return $layoutFactory;
        });

        $this->app->singleton('Layout\Element\Factory\FactoryJson', function () {
            /** @var \Illuminate\Events\Dispatcher $eventDispatcher */
            $eventDispatcher = $this->app->make('Illuminate\Events\Dispatcher');
            $layoutFactory = new FactoryJson($this->app);
            $layoutFactory->registerDefaultModel('Layout\Element\Type\TypeDefault');
            $layoutFactory->registerDefaultOutputModel('Layout\Element\Output\Json\JsonDefault');

            $eventDispatcher->fire('layout.initialize.factory.json', array($layoutFactory));

            return $layoutFactory;
        });
    }
    
}

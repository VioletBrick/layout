<?php
/** {license_text}  */
namespace Layout;

use Illuminate\Support\Fluent;
use Illuminate\Support\ServiceProvider as ServiceProviderAbstract;
use Layout\Element\Factory\FactoryHtml;
use Layout\Element\Factory\FactoryJson;

class ServiceProvider 
    extends ServiceProviderAbstract 
{
    protected $supportedFormats = array(
        FactoryHtml::TYPE_CODE,
        FactoryJson::TYPE_CODE
    );

    protected $elementTypeSchema;
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
     * @param $name
     * @param array $elementConfig
     */
    protected function registerType($name, array $elementConfig)
    {
        foreach ($this->supportedFormats as $format) {
            if (isset($elementConfig['model'])) {
                $this->elementTypeSchema[$format][$name] = [
                    'model'        => $elementConfig['model'],
                    'output_model' => isset($elementConfig['output_model'][$format]) ? $elementConfig['output_model'][$format] : null,
                ];
            }
        }
    }
    
    protected function initElementTypeSchema()
    {
        if (is_null($this->elementTypeSchema)) {
            $this->elementTypeSchema = [];
            /** @var \Illuminate\Events\Dispatcher $eventDispatcher */
            $eventDispatcher = $this->app->make('Illuminate\Events\Dispatcher');
            /** @var \Illuminate\Contracts\Config\Repository $config */
            $config = $this->app->make('Illuminate\Contracts\Config\Repository');

            $transport = new Fluent();
            $transport['schema'] = $config['layout.element'] ?: [];

            $eventDispatcher->fire('layout.initialize.element.schema', array($transport));

            if (is_array($transport['schema'])) {
                foreach ($transport['schema'] as $name => $elementConfig) {
                    if (is_array($elementConfig)) {
                        $this->registerType($name, $elementConfig);
                    }
                }
            }
        }
    }

    /**
     * Register default element types
     */
    public function register()
    {
        $this->app->singleton('Layout\Element\Factory\FactoryHtml', function ()
        {
            $this->initElementTypeSchema();
            
            /** @var \Illuminate\Events\Dispatcher $eventDispatcher */
            $eventDispatcher = $this->app->make('Illuminate\Events\Dispatcher');
            $layoutFactory = new FactoryHtml($this->app);
            $layoutFactory->registerDefaultModel('Layout\Element\Type\TypeDefault');
            $layoutFactory->registerDefaultOutputModel('Layout\Element\Output\Html\HtmlDefault');
            
            foreach ($this->elementTypeSchema[$layoutFactory::TYPE_CODE] as $name => $typeConfig) {
                $layoutFactory->register($name, $typeConfig['model'], $typeConfig['output_model']);
            }
            
            $eventDispatcher->fire('layout.initialize.factory.'.$layoutFactory::TYPE_CODE, array($layoutFactory));

            return $layoutFactory;
        });

        $this->app->singleton('Layout\Element\Factory\FactoryJson', function ()
        {
            $this->initElementTypeSchema();
            
            /** @var \Illuminate\Events\Dispatcher $eventDispatcher */
            $eventDispatcher = $this->app->make('Illuminate\Events\Dispatcher');
            $layoutFactory = new FactoryJson($this->app);
            $layoutFactory->registerDefaultModel('Layout\Element\Type\TypeDefault');
            $layoutFactory->registerDefaultOutputModel('Layout\Element\Output\Json\JsonDefault');

            foreach ($this->elementTypeSchema[$layoutFactory::TYPE_CODE] as $name => $typeConfig) {
                $layoutFactory->register($name, $typeConfig['model'], $typeConfig['output_model']);
            }

            $eventDispatcher->fire('layout.initialize.factory.'.$layoutFactory::TYPE_CODE, array($layoutFactory));

            return $layoutFactory;
        });
    }
    
}

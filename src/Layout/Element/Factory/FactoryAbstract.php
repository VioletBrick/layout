<?php
/** {license_text}  */ 
namespace Layout\Element\Factory;

use Illuminate\Contracts\Container\Container as ContainerContract;
use Layout\Element\Output\OutputInterface;
use Layout\Element\Type\TypeInterface;

abstract class FactoryAbstract
    implements FactoryInterface
{
    protected $container;
    protected $modelMap = array();
    protected $outputModelMap = array();
    protected $defaultModel;
    protected $defaultOutputModel;
    protected $modelPrefix;
    protected $outputModelPrefix;

    /**
     * @return string
     */
    abstract protected function getTypeCode();

    /**
     * @param ContainerContract|\Illuminate\Foundation\Application $container
     */
    public function __construct(ContainerContract $container)
    {
        $this->container         = $container;
        $this->modelPrefix       = sprintf('layout.element.model.%s', $this->getTypeCode());
        $this->outputModelPrefix = sprintf('layout.element.output.%s', $this->getTypeCode());
    }

    /**
     * @param string $type
     * @param string $modelAbstract
     * @param null|string $outputModelAbstract
     * @throws FactoryException
     */
    public function register($type, $modelAbstract, $outputModelAbstract = null)
    {
        if (is_null($outputModelAbstract)) {
            if ($this->defaultOutputModel) {
                $outputModelAbstract = $this->defaultOutputModel;
            } else {
                throw new FactoryException(sprintf('Output model or default output model not defined in factory "%s" for type: "%s"', $this->getTypeCode(), $type));
            }
        }
        
        $modelAlias = sprintf('%s.%s', $this->modelPrefix, $type);
        $this->container->bind($modelAlias, $modelAbstract);
        $this->modelMap[$type] = $modelAlias;

        $outputModelAlias = sprintf('%s.%s', $this->outputModelPrefix, $type);
        $this->container->bind($outputModelAlias, $outputModelAbstract);
        $this->outputModelMap[$type] = $outputModelAlias;
    }

    /**
     * @param string $abstract
     */
    public function registerDefaultModel($abstract)
    {
        $this->defaultModel = $abstract;
    }

    /**
     * @param string $abstract
     */
    public function registerDefaultOutputModel($abstract)
    {
        $this->defaultOutputModel = $abstract;
    }

    /**
     * @param string $type
     * @param array $parameters
     * @return TypeInterface
     */
    public function resolve($type, array $parameters = array())
    {
        if (isset($this->modelMap[$type])) {
            $instance = $this->container->make($this->modelMap[$type], array($this->resolveOutputModel($type)));
        } else {
            $instance = $this->container->make($this->defaultModel, array($this->resolveOutputModel($type)));
        }
        
        return $instance;
    }

    /**
     * @param $type
     * @return mixed|object
     */
    protected function resolveOutputModel($type)
    {
        if (isset($this->outputModelMap[$type])) {
            $instance = $this->container->make($this->outputModelMap[$type]);
        } else {
            $instance = $this->container->make($this->defaultOutputModel);
        }

        return $instance;
    }
    
}

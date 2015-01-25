<?php
/** {license_text}  */
namespace Layout\Processor;

use Illuminate\Foundation\Application;
use Layout\LayoutConfigInterface;
use Layout\Element\BuilderInterface;
use Layout\Element\Factory\FactoryInterface;
use Layout\Element\Type\TypeInterface as ElementTypeInterface;

abstract class ProcessorAbstract
    implements ProcessorInterface
{
    /** @var  BuilderInterface */
    protected $builder;
    /** @var  FactoryInterface */ 
    protected $factory;
    /** @var  FormatInterface */
    protected $format;
    /** @var  ElementTypeInterface */
    protected $rootElement;
    
    /**
     * @param BuilderInterface $builder
     */
    public function setBuilder(BuilderInterface $builder)
    {
        $this->builder = $builder;
    }

    /**
     * @param FactoryInterface $factory
     */
    public function setFactory(FactoryInterface $factory)
    {
        $this->factory = $factory;
    }

    /**
     * @param LayoutConfigInterface $layoutConfig
     * @return mixed
     * @throws ProcessorException
     */
    public function run(LayoutConfigInterface $layoutConfig)
    {
        if (!$this->factory instanceof FactoryInterface) {
            throw new ProcessorException("Element Factory not defined");
        }

        if (!$this->builder instanceof BuilderInterface) {
            throw new ProcessorException("Element Builder not defined");
        }
        
        $rootElement = $this->builder->buildStructure($layoutConfig, $this->factory);
        
        return $rootElement->getOutput();
    }
}

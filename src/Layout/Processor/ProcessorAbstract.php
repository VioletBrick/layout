<?php
/** {license_text}  */
namespace Layout\Processor;

use Illuminate\Foundation\Application;
use Layout\BuilderInterface;
use Layout\LayoutConfigInterface;
use Layout\Element\Factory\FactoryInterface;
use Layout\Output\FormatInterface;
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
     * @param FormatInterface $format
     */
    public function setFormat(FormatInterface $format)
    {
        $this->format = $format;
    }

    /**
     * @param LayoutConfigInterface $layoutConfig
     * @return mixed
     * @throws ProcessorException
     */
    public function run(LayoutConfigInterface $layoutConfig)
    {
        $outputFormat = $this->format;
        $factory      = $this->factory;

        if (!$outputFormat instanceof FormatInterface) {
            throw new ProcessorException("Output format not defined");
        }

        if (!$factory instanceof FactoryInterface) {
            throw new ProcessorException("Element Factory not defined");
        }
        
        $rootElement = $this->builder->buildStructure($layoutConfig, $this->factory);
        
        return $outputFormat->format($rootElement->getOutput());
    }
}

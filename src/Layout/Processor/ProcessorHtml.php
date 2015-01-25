<?php
/** {license_text}  */ 
namespace Layout\Processor;

use Layout\Element\Builder;
use Layout\Element\Factory\FactoryHtml;

class ProcessorHtml
    extends ProcessorAbstract
{
    /**
     * @param Builder $builder
     * @param FactoryHtml $factory
     */
    public function __construct(Builder $builder, FactoryHtml $factory)
    {
        $this->setBuilder($builder);
        $this->setFactory($factory);
    }
}


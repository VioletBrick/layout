<?php
/** {license_text}  */ 
namespace Layout\Processor;

use Layout\Element\Builder;
use Layout\Element\Factory\FactoryJson;

class ProcessorJson
    extends ProcessorAbstract
{
    /**
     * @param Builder $builder
     * @param FactoryJson $factory
     */
    public function __construct(Builder $builder, FactoryJson $factory)
    {
        $this->setBuilder($builder);
        $this->setFactory($factory);
    }
}


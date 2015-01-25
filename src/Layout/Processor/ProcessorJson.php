<?php
/** {license_text}  */ 
namespace Layout\Processor;

use Layout\Builder;
use Layout\Element\Factory\FactoryJson;
use Layout\Output\FormatJson;

class ProcessorJson
    extends ProcessorAbstract
{
    /**
     * @param Builder $builder
     * @param FormatJson $format
     * @param FactoryJson $factory
     */
    public function __construct(Builder $builder, FormatJson $format, FactoryJson $factory)
    {
        $this->setBuilder($builder);
        $this->setFormat($format);
        $this->setFactory($factory);
    }
}


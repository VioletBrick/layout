<?php
/** {license_text}  */ 
namespace Layout\Processor;

use Layout\Builder;
use Layout\Element\Factory\FactoryHtml;
use Layout\Output\FormatHtml;

class ProcessorHtml
    extends ProcessorAbstract
{
    /**
     * @param Builder $builder
     * @param FormatHtml $format
     * @param FactoryHtml $factory
     */
    public function __construct(Builder $builder, FormatHtml $format, FactoryHtml $factory)
    {
        $this->setBuilder($builder);
        $this->setFormat($format);
        $this->setFactory($factory);
    }
}


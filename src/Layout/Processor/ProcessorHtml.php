<?php
/** {license_text}  */ 
namespace Layout\Processor;

use Layout\Element\Factory\FactoryHtml;

class ProcessorHtml
    extends ProcessorAbstract
{
    /**
     * @param FactoryHtml $factory
     */
    public function __construct(FactoryHtml $factory)
    {
        $this->setFactory($factory);
    }
}


<?php
/** {license_text}  */ 
namespace Layout\Processor;

use Layout\Element\Builder;
use Layout\Element\Factory\FactoryJson;

class ProcessorJson
    extends ProcessorAbstract
{
    /**
     * @param FactoryJson $factory
     */
    public function __construct(FactoryJson $factory)
    {
        $this->setFactory($factory);
    }
}


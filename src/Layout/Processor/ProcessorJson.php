<?php
/** {license_text}  */ 
namespace Layout\Processor;

use Layout\Element\Factory\FactoryJson;
use Layout\Element\Type\DataTransportChildren;
use Layout\Element\Type\DataTransportProtected;
use Layout\Element\Type\DataTransportPublic;

class ProcessorJson
    extends ProcessorAbstract
{
    /**
     * @param FactoryJson $factory
     * @param DataTransportPublic $dataTransportPublic
     * @param DataTransportProtected $dataTransportProtected
     * @param DataTransportChildren $dataTransportChildren
     */
    public function __construct(
        FactoryJson $factory,
        DataTransportPublic $dataTransportPublic,
        DataTransportProtected $dataTransportProtected,
        DataTransportChildren $dataTransportChildren
    )
    {
        parent::__construct($factory, $dataTransportPublic, $dataTransportProtected, $dataTransportChildren);
    }
}


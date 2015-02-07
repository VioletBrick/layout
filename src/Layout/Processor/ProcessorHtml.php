<?php
/** {license_text}  */ 
namespace Layout\Processor;

use Layout\Element\Factory\FactoryHtml;
use Layout\Element\Type\DataTransportChildren;
use Layout\Element\Type\DataTransportProtected;
use Layout\Element\Type\DataTransportPublic;

class ProcessorHtml
    extends ProcessorAbstract
{
    /**
     * @param FactoryHtml $factory
     * @param DataTransportPublic $dataTransportPublic
     * @param DataTransportProtected $dataTransportProtected
     * @param DataTransportChildren $dataTransportChildren
     */
    public function __construct(
        FactoryHtml $factory,
        DataTransportPublic $dataTransportPublic,
        DataTransportProtected $dataTransportProtected,
        DataTransportChildren $dataTransportChildren
    )
    {
        parent::__construct($factory, $dataTransportPublic, $dataTransportProtected, $dataTransportChildren);
    }
}


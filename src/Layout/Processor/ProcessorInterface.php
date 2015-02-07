<?php
/** {license_text}  */
namespace Layout\Processor;

use Layout\ConfigInterface;
use Layout\Element\Type\TypeInterface;

interface ProcessorInterface
{
    /**
     * @param ConfigInterface $layoutConfig
     * @return mixed
     */
    public function run(ConfigInterface $layoutConfig);
}

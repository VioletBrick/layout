<?php
/** {license_text}  */
namespace Layout\Processor;

use Layout\ConfigInterface;
use Layout\Element\Type\TypeInterface;

interface ProcessorInterface
{
    /**
     * @param ConfigInterface $layoutConfig
     * @return TypeInterface
     */
    public function build(ConfigInterface $layoutConfig);
    /**
     * @param ConfigInterface $layoutConfig
     * @return mixed
     */
    public function run(ConfigInterface $layoutConfig);
}

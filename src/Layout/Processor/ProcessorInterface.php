<?php
/** {license_text}  */
namespace Layout\Processor;

use Illuminate\Foundation\Application;
use Layout\LayoutConfigInterface;
use Layout\Element\Type\TypeInterface;

interface ProcessorInterface
{
    /**
     * @param LayoutConfigInterface $layoutConfig
     * @return TypeInterface
     */
    public function build(LayoutConfigInterface $layoutConfig);
    /**
     * @param LayoutConfigInterface $layoutConfig
     * @return mixed
     */
    public function run(LayoutConfigInterface $layoutConfig);
}

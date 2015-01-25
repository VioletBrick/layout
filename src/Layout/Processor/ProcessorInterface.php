<?php
/** {license_text}  */
namespace Layout\Processor;

use Illuminate\Foundation\Application;
use Layout\LayoutConfigInterface;

interface ProcessorInterface
{
    /**
     * @param LayoutConfigInterface $layoutConfig
     * @return mixed
     */
    public function run(LayoutConfigInterface $layoutConfig);
}

<?php
/** {license_text}  */
namespace Layout;

use Illuminate\Foundation\Application;

interface RendererInterface
{
    public function addElementType($typeCode, $abstract);
    public function getElement($xpath);
    public function prepare(ConfigInterface $layoutConfig);
    public function render();
}

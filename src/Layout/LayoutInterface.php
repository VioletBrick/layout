<?php
namespace Layout;
/** {license_text}  */ 

interface LayoutInterface
{
    public function load(array $handles = [], $useDefault = true);
    public function render();

}

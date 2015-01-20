<?php
namespace Layout;
use Layout\Output\FormatInterface;

/** {license_text}  */ 

interface LayoutInterface
{
    public function process(FormatInterface $format, $handles = [], $useDefault = true);
}

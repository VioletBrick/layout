<?php

namespace Layout;

/** {license_text}  */ 
interface ElementInterface
{
    public function prepare();
    public function addChild();
    public function getChild();
    public function getParent();
    public function render();
}

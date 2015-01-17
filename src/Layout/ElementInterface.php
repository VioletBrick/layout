<?php

namespace Layout;

use ArrayAccess;
use JsonSerializable;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Contracts\Support\Arrayable;

/** {license_text}  */ 
interface ElementInterface
    extends ArrayAccess, Arrayable, Jsonable, JsonSerializable
{
    public function __construct(RendererInterface $renderer);
    public function setConfig(array $elementConfig);
    public function prepare();
    public function addChild(ElementInterface $element);
    public function getChild($name = null);
    public function hasChild();
    public function setParent(ElementInterface $element);
    public function getParent();
    public function render();
}

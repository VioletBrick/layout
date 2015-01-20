<?php

namespace Layout;

use ArrayAccess;
use Layout\Support\Fluent;
use JsonSerializable;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Contracts\Support\Arrayable;

/** {license_text}  */ 
interface ElementInterface
    extends ArrayAccess, Arrayable, Jsonable, JsonSerializable
{
    /**
     * @param array $data
     */
    public function setData(array $data);

    /**
     * Retrieve element public data
     * 
     * @return Fluent|array
     */
    public function getPublicData();

    /**
     * @param ElementInterface $element
     * @param null $name
     */
    public function addChild(ElementInterface $element, $name = null);

    /**
     * @param string|null $name
     * @return mixed
     */
    public function getChild($name = null);

    /**
     * @return bool
     */
    public function hasChild();

    /**
     * @param string|null $name
     */
    public function removeChild($name = null);

    /**
     * @param ElementInterface $element
     */
    public function setParent(ElementInterface $element);

    /**
     * @return ElementInterface
     */
    public function getParent();
}

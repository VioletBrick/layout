<?php
/** {license_text}  */
namespace Layout\Element\Type;

use ArrayAccess;
use Layout\Support\Fluent;
use JsonSerializable;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Contracts\Support\Arrayable;
use Layout\Support\FluentInterface;

interface TypeInterface
    extends ArrayAccess, Arrayable, Jsonable, JsonSerializable, FluentInterface
{
    /**
     * @param array $data
     */
    public function setData(array $data);

    /**
     * @return mixed
     */
    public function getOutput();

    /**
     * @param TypeInterface $element
     * @param null $name
     */
    public function addChild(TypeInterface $element, $name = null);

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
     * @param TypeInterface $element
     */
    public function setParent(TypeInterface $element);

    /**
     * @return TypeInterface
     */
    public function getParent();
}

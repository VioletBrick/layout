<?php

namespace Layout;
use Layout\Output\FormatInterface;
use Layout\Support\Fluent;
use Layout\Support\FluentInterface;
use Layout\Support\FluentTrait;

/** {license_text}  */ 
abstract class ElementAbstract
    implements ElementInterface, FluentInterface
{
    use FluentTrait;
    /** @var  FormatInterface */
    protected $format;
    /** @var  ElementInterface */
    protected $parent;
    protected $children      = array();
    protected $publicData    = array();
    protected $protectedData = array();

    /**
     * @param $name
     * @param array $data
     */
    public function __construct($name, array $data = array())
    {
        $data['name']        = $name;
        $this->attributes    = $data;
        $this->publicData    = array();
        $this->protectedData = array();
        $this->initialize($this->publicData, $this->protectedData);
    }

    abstract protected function initialize(&$publicData);

    /**
     * @param array $data
     */
    public function setData(array $data)
    {
        $this->attributes = $data;
    }

    /**
     * @param ElementInterface $element
     * @param null $name
     */
    public function addChild(ElementInterface $element, $name = null)
    {
        $name = ($name ?: $element['name'] ?: uniqid('nameless_'));
        $this->children[$name] = $element;
        $element->setParent($this);
    }

    /**
     * @param null $name
     * @return array|bool|ElementInterface
     */
    public function getChild($name = null)
    {
        if (is_null($name)) {
            return $this->children;
        }
        
        if (isset($this->children[$name])) {
            return $this->children[$name];
        }
        
        return false;
    }

    /**
     * @param string|null $name
     */
    public function removeChild($name = null)
    {
        if ($name) {
            if (isset($this->children[$name])) {
                unset($this->children[$name]);
            }
        } else {
            $this->children = array();
        }
    }

    /**
     * @return bool
     */
    public function hasChild()
    {
        return !empty($this->children);
    }

    /**
     * @param ElementInterface $element
     */
    public function setParent(ElementInterface $element)
    {
        $this->parent = $element;
    }

    /**
     * @return ElementInterface
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @return null
     */
    public function __invoke()
    {
        return null;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return '';
    }

    /**
     * @return array|Fluent
     */
    public function getPublicData()
    {
        return $this->publicData;
    }

    /**
     * @param \ArrayAccess|array $target
     * @param \ArrayAccess|array $source
     * @param array $map
     */
    protected function fill(&$target, &$source, array $map)
    {
        foreach ($map as $key) {
            $target[$key] = $source[$key];
        }
    }
}

<?php

namespace Layout;
use Illuminate\Support\Fluent;

/** {license_text}  */ 
abstract class ElementAbstract
    extends Fluent
    implements ElementInterface
{
    /** @var  RendererInterface  */
    protected $renderer;
    /** @var  ElementInterface */
    protected $parent;
    protected $children = array();

    /**
     * @param RendererInterface $renderer
     */
    public function __construct(RendererInterface $renderer)
    {
        $this->renderer = $renderer;
    }
    
    /**
     * @param array $elementConfig
     */
    public function setConfig(array $elementConfig)
    {
        foreach ($elementConfig as $key => $value) {
            if (!is_array($value) && !is_object($value)) {
                $this->attributes[$key] = $value;
            }
        }
    }

    /**
     * @param ElementInterface $element
     */
    public function addChild(ElementInterface $element)
    {
        $name = $element['name'] ? $element['name'] : uniqid('nameless_');
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
     * Prepare layout
     */
    public function prepare()
    {
        
    }
}

<?php

namespace Layout;
use Illuminate\Support\Fluent;

/** {license_text}  */ 
abstract class ElementAbstract
    extends Fluent
    implements ElementInterface
{
    /** @var  RendererInterface */
    protected $renderer;
    /** @var  ConfigInterface */
    protected $config;
    /** @var  ElementInterface */
    protected $parent;
    protected $children = array();

    /**
     * @param RendererInterface $renderer
     * @param ConfigInterface $config
     */
    public function __construct(RendererInterface $renderer, ConfigInterface $config)
    {
        $this->renderer = $renderer;
        $this->config   = $config;
    }
    
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
     * @param null $name
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
     * Prepare layout
     */
    public function prepare()
    {
        
    }

    /**
     * @param $name
     * @return string
     */
    public function renderChild($name)
    {
        return isset($this->children[$name]) ? $this->children[$name]->render() : '';
    }

    /**
     * @return mixed
     */
    public function __invoke()
    {
        return null;
    }

    /**
     * @return mixed
     */
    public function __toString()
    {
        return '';
    }
}

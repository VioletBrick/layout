<?php
/** {license_text}  */
namespace Layout\Element\Type;

use Layout\Element\Output\OutputInterface as ElementOutputInterface;
use Layout\Output\FormatInterface;
use Layout\Support\FluentInterface;
use Layout\Support\FluentTrait;
use Predis\Connection\FactoryInterface;

abstract class TypeAbstract
    implements TypeInterface
{
    use FluentTrait;
    
    /** @var  FormatInterface */
    protected $format;
    /** @var  TypeInterface */
    protected $parent;
    protected $children = array();
    /** @var  ElementOutputInterface */
    protected $output;
    protected $prepared = false;
    
    /**
     * @param ElementOutputInterface $outputInterface
     */
    public function __construct(ElementOutputInterface $outputInterface)
    {
        $this->output = $outputInterface;
    }

    /**
     * Prepare data for frontend model
     * 
     * @return array
     */
    protected function getPublicData()
    {
        return array();
    }

    /**
     * Prepare hidden data for frontend model
     * 
     * @return array
     */
    protected function getHiddenData()
    {
        return array();
    }
    
    /**
     * @return mixed
     */
    public function getOutput()
    {
        $output     = $this->output;
        $output->setPublicData($this->getPublicData());
        $output->setHiddenData($this->getHiddenData());
        
        if ($this->hasChild()) {
            foreach ($this->getChild() as $name => $childElement) {
                $output->addChildOutputResult($name, $childElement->getOutput());
            }
        }

        /** @var ElementOutputInterface $outputModel */
        return $output->getOutput();
    }

    /**
     * @param array $data
     */
    public function setData(array $data)
    {
        $this->attributes = $data;
    }

    /**
     * @param TypeInterface $element
     * @param null $name
     */
    public function addChild(TypeInterface $element, $name = null)
    {
        $name = ($name ?: $element['name'] ?: uniqid('nameless_'));
        $this->children[$name] = $element;
        $element->setParent($this);
    }

    /**
     * @param null $name
     * @return array|bool|TypeInterface
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
     * @param TypeInterface $element
     */
    public function setParent(TypeInterface $element)
    {
        $this->parent = $element;
    }

    /**
     * @return TypeInterface
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
     * @param $target
     * @param $source
     * @param array $map
     * @return array|FactoryInterface
     * @throws TypeException
     */
    protected function fill($target, $source, array $map)
    {
        if ((is_array($target) || $target instanceof FluentInterface) 
            && (is_array($source) || $source instanceof FluentInterface)) {
            foreach ($map as $key) {
                $target[$key] = $source[$key];
            }
        } else {
            throw new TypeException('Invalid data type for fill method');
        }
        
        return $target;
    }
}

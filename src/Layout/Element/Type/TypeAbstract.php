<?php
/** {license_text}  */
namespace Layout\Element\Type;

use Core\Support\Fluent;
use Core\Support\FluentInterface;
use Core\Support\FluentTrait;
use Illuminate\Support\Collection;
use Layout\Element\Output\OutputInterface as ElementOutputInterface;
use Layout\Element\Factory\FactoryInterface;

abstract class TypeAbstract
    implements TypeInterface
{
    use FluentTrait;
    
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
     * Before output initialization
     */
    protected function beforeOutput()
    {
        
    }

    /**
     * @param $data
     * @return Fluent|null
     */
    protected function prepareData($data)
    {
        if (is_object($data)) {
            if ($data instanceof Collection) {
                $data = $data->all();
            } else {
                $class = get_class($data);
                // Ignore fluent objects
                if ($class != 'Core\Support\Fluent' && $class != 'Illuminate\Support\Fluent') {
                    if (method_exists($data, 'toArray')) {
                        $data = new Fluent($data->toArray());
                    }
                }
            }
        }
        
        if (is_array($data) || $data instanceof Fluent || $data instanceof \Illuminate\Support\Fluent) {
            foreach ($data as $key => $value) {
                if (is_object($value) || is_array($value)) {
                    $data[$key] = $this->prepareData($value);
                } else {
                    $data[$key] = $value;
                }
            }
            
            return $data;
        }
        
        return null;
    }
    
    /**
     * @return mixed
     */
    public function getOutput()
    {
        $this->beforeOutput();
        
        $output = $this->output;
        
        if ($data = $this->prepareData($this->getPublicData())) {
            $output->setPublicData($data);
        }

        if ($data = $this->prepareData($this->getHiddenData())) {
            $output->setHiddenData($data);
        }
        
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
        $this->setAttributes($data);
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

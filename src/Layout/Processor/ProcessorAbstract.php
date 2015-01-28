<?php
/** {license_text}  */
namespace Layout\Processor;

use Layout\ConfigInterface;
use Layout\Element\Factory\FactoryInterface;
use Layout\Element\Type\TypeInterface as ElementTypeInterface;

abstract class ProcessorAbstract
    implements ProcessorInterface
{
    /** @var  FactoryInterface */ 
    protected $factory;
    /** @var  ElementTypeInterface */
    protected $rootElement;

    /**
     * @param FactoryInterface $factory
     */
    public function setFactory(FactoryInterface $factory)
    {
        $this->factory = $factory;
    }

    /**
     * @param array $elementConfig
     * @return ElementTypeInterface
     */
    protected function createElement(array $elementConfig)
    {
        $elementData = array();
        foreach ($elementConfig as $key => $value) {
            if (0 === strpos($key, '_')) {
                $elementData[substr($key, 1)] = $value;
            }
        }

        $type    = (isset($elementData['type']) ? $elementData['type'] : null);
        $element =  $this->factory->resolve($type);
        $element->setAttributes($elementData);

        return $element;
    }

    /**
     * @param $name
     * @return bool
     */
    protected function isValidElementName($name)
    {
        return 0 < preg_match('/^[^_]\w+$/ui', $name);
    }

    /**
     * @param array $layoutConfig
     * @param ElementTypeInterface $parent
     * @return array
     */
    protected function generateElements(array $layoutConfig, ElementTypeInterface $parent = null)
    {
        $elements = array();
        foreach ($layoutConfig as $name => $elementConfig) {
            if ($this->isValidElementName($name)) {
                $elements[$name] = $this->createElement(is_array($elementConfig) ? $elementConfig : array());
                if ($parent) {
                    $parent->addChild($elements[$name], $name);
                }

                if (is_array($elementConfig)) {
                    $this->generateElements($elementConfig, $elements[$name]);
                }
            }
        }

        return $elements;
    }

    /**
     * @param ConfigInterface $layoutConfig
     * @return ElementTypeInterface
     * @throws ProcessorException
     */
    public function build(ConfigInterface $layoutConfig)
    {
        if (!$this->factory instanceof FactoryInterface) {
            throw new ProcessorException("Element Factory not defined");
        }

        $rootElement = $this->createElement([]);
        $this->generateElements($layoutConfig->toArray(), $rootElement);
        
        return $rootElement;
    }

    /**
     * @param ConfigInterface $layoutConfig
     * @return mixed
     * @throws ProcessorException
     */
    public function run(ConfigInterface $layoutConfig)
    {
        return $this->build($layoutConfig)->getOutput();
    }
}

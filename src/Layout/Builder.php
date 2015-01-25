<?php
/** {license_text}  */
namespace Layout;

use Layout\Element\Factory\FactoryInterface;
use Layout\Element\Type\TypeInterface as ElementTypeInterface;

class Builder
    implements BuilderInterface
{
    /** @var  FactoryInterface */
    protected $elementTypeFactory;
    /** @var  ElementTypeInterface */
    protected $rootElement;

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
        $element =  $this->elementTypeFactory->resolve($type);
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
                $this->generateElements($elementConfig, $elements[$name]);
            }
        }

        return $elements;
    }

    /**
     * @param LayoutConfigInterface $layoutConfig
     * @param FactoryInterface $elementTypeFactory
     * @return ElementTypeInterface
     */
    public function buildStructure(LayoutConfigInterface $layoutConfig, FactoryInterface $elementTypeFactory)
    {
        $this->elementTypeFactory = $elementTypeFactory;
        $rootElement = $this->createElement([]);
        $this->generateElements($layoutConfig->toArray(), $rootElement);
        
        return $rootElement;
    }
}

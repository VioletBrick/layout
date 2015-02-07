<?php
/** {license_text}  */
namespace Layout\Processor;

use Layout\ConfigInterface;
use Layout\Element\Factory\FactoryInterface;
use Layout\Element\Type\DataTransportChildren;
use Layout\Element\Type\DataTransportProtected;
use Layout\Element\Type\DataTransportPublic;
use Layout\Element\Type\TypeInterface as ElementTypeInterface;

abstract class ProcessorAbstract
    implements ProcessorInterface
{
    /** @var  FactoryInterface */ 
    protected $factory;
    /** @var  ElementTypeInterface */
    protected $rootElement;
    /** @var  DataTransportPublic */
    protected $dataTransportPublic;
    /** @var  DataTransportProtected */
    protected $dataTransportProtected;
    /** @var  DataTransportChildren */
    protected $dataTransportChildren;

    /**
     * @param FactoryInterface $factory
     * @param DataTransportPublic $dataTransportPublic
     * @param DataTransportProtected $dataTransportProtected
     * @param DataTransportChildren $dataTransportChildren
     */
    public function __construct(
        FactoryInterface $factory, 
        DataTransportPublic $dataTransportPublic, 
        DataTransportProtected $dataTransportProtected, 
        DataTransportChildren $dataTransportChildren
    )
    {
        $this->factory                = $factory;
        $this->dataTransportPublic    = $dataTransportPublic;
        $this->dataTransportProtected = $dataTransportProtected;
        $this->dataTransportChildren  = $dataTransportChildren;
    }

    /**
     * @param array $elementConfig
     * @return array
     */
    protected function extractElementAttributes(array &$elementConfig)
    {
        $attributes = [];
        foreach ($elementConfig as $key => $value) {
            if (0 === strpos($key, '_')) {
                $k = substr($key, 1);
                $attributes[$k] = $value;
                unset($elementConfig[$key]);
            }
        }
        
        return $attributes;
    }

    /**
     * @param $name
     * @return bool
     */
    protected function isElementNode($name)
    {
        return 0 < preg_match('/^[^_]\w+$/ui', $name);
    }

    /**
     * @param array $elements
     * @param string $path
     * @return array
     */
    protected function processElements(array $elements, $path = '')
    {
        $result = [];
        foreach ($elements as $elementName => $elementsConfig) {
            $elementPath = "{$path}/{$elementName}";
            $attributes = ['path' => $elementPath];
            if (is_array($elementsConfig)) {
                $children   = [];
                if (!empty($elementsConfig)) {
                    $attributes += $this->extractElementAttributes($elementsConfig);
                }
                if (!empty($elementsConfig)) {
                    $children += $this->processElements($elementsConfig, $elementPath);
                }
                $this->dataTransportChildren->setAttributes($children);
            }
            $this->dataTransportProtected->setAttributes($attributes);

            $typeModel = $this->factory->resolve($this->dataTransportProtected['type']);
            $result[$elementName] = $typeModel->processOutput($this->dataTransportPublic, $this->dataTransportProtected, $this->dataTransportChildren);

            $this->dataTransportPublic->clearAttributes();
            $this->dataTransportProtected->clearAttributes();
            $this->dataTransportChildren->clearAttributes();
        }

        return $result;
    }

    /**
     * @param ConfigInterface $layoutConfig
     * @return mixed
     * @throws ProcessorException
     */
    public function run(ConfigInterface $layoutConfig)
    {
        $elements = [
            'root' => $layoutConfig->toArray()
        ];
        
        $result = $this->processElements($elements);
        
        return isset($result['root']) ? $result['root'] : null;
    }
}

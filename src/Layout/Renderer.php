<?php
/** {license_text}  */
namespace Layout;

use Illuminate\Foundation\Application;

class Renderer
    implements RendererInterface
{
    protected $app;
    protected $rootElements = array();

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * @param $xpath
     * @return bool|ElementInterface
     */
    public function getElement($xpath)
    {
        $path = explode('/', trim($xpath, '/'));
        $name = array_shift($path);
        
        if (isset($this->rootElements[$name])) {
            /** @var ElementInterface $element */
            $element = $this->rootElements[$name];
            while(count($path)) {
                $name = array_shift($path);
                $element = $element->getChild($name);
                if (!$element) {
                    break;
                }
            }
            
            if ($element instanceof ElementInterface) {
                return $element;
            }
        }
        
        return false;
    }
    

    /**
     * @param null $type
     * @return ElementInterface
     */
    protected function getElementInstance($type = null)
    {
        $type = $type ?: 'simple';
        $type = "layout.element.{$type}";
        
        return $this->app->make($type, array($this));
    }

    /**
     * @param array $elementConfig
     * @return ElementInterface
     * @throws RendererException
     */
    protected function createElement($name, array $elementConfig)
    {
        $elementData = array();
        foreach ($elementConfig as $key => $value) {
            if (0 === strpos($key, '_')) {
                $elementData[substr($key, 1)] = $value;
            }
        }
        
        $elementData['name'] = $name;

        $type    = isset($elementData['type']) ? $elementData['type'] : null;
        $element = $this->getElementInstance($type);
        $element->setData($elementData);

        if (!$element instanceof ElementInterface) {
            throw new RendererException(sprintf('Incorrect element type: "%s".', var_export($type, true)));
        }
        
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
     * @return array
     * @throws RendererException
     */
    protected function generateElements(array $layoutConfig)
    {
        $elements = array();
        foreach ($layoutConfig as $name => $elementConfig) {
            if ($this->isValidElementName($name)) {
                $elements[$name] = $this->createElement($name, is_array($elementConfig) ? $elementConfig : array());
                if (is_array($elementConfig)) {
                    foreach ($this->generateElements($elementConfig) as $childName => $child) {
                        $elements[$name]->addChild($child, $childName);
                    }
                }
            }
        }
        
        return $elements;
    }

    /**
     * @param array $elements
     */
    protected function prepareElements(array $elements)
    {
        foreach ($elements as $element) {
            /** @var ElementInterface $element */
            $element->prepare();
            if ($element->hasChild()) {
                $this->prepareElements($element->getChild());
            }
        }
    }

    /**
     * @param ConfigInterface $layoutConfig
     */
    public function prepare(ConfigInterface $layoutConfig)
    {
        $this->rootElements = $this->generateElements($layoutConfig->toArray());
        
        $this->prepareElements($this->rootElements);
    }

    /**
     * @return string
     */
    public function render()
    {
        $output = '';
        
        foreach ($this->rootElements as $element) {
            /** @var ElementInterface $element */
            $output = sprintf("%s%s", $output, $element->render());
        }
        
        return $output;
    }
}

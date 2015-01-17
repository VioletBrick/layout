<?php
/** {license_text}  */
namespace Layout;

use Illuminate\Foundation\Application;

class Renderer
    implements RendererInterface
{
    protected $app;
    protected $types          = array();
    protected $rootElements   = array();

    public function addElementType($typeCode, $abstract)
    {
        $this->types[$typeCode];
    }

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
        
        return $this->app->make("layout.element.{$type}", array($this));
    }

    /**
     * @param array $elementConfig
     * @return ElementInterface
     * @throws RendererException
     */
    protected function createElement(array $elementConfig)
    {
        $type = $elementConfig['type'] ?: null;
        $element = $this->getElementInstance($elementConfig['type'] ?: null);
        
        if (!$element instanceof ElementInterface) {
            throw new RendererException(sprintf('Incorrect element type: "%s".', var_export($type, true)));
        }
        
        return $element;
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
            $element = $this->createElement($elementConfig);
            $elements[$name] = $element;
            if ($layoutConfig['children']) {
                foreach ($this->generateElements($layoutConfig['children']) as $children) {
                    $element->addChild($children);
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

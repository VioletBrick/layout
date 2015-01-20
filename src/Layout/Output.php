<?php
/** {license_text}  */
namespace Layout;

use Illuminate\Foundation\Application;
use Layout\Output\FormatInterface;

class Output
    implements OutputInterface
{
    protected $app;
    protected $rootElement;
    /** @var  FormatInterface */
    protected $format;
    
    protected $elementModels = [];
    
    /**
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * @param $code
     */
    public function registerElementTypeModel($code)
    {
        $this->elementModels[] = $this->getElementIocAlias($code);
    }

    /**
     * @param FormatInterface $format
     */
    public function setFormat(FormatInterface $format)
    {
        $this->format = $format;
    }

    /**
     * @return string
     */
    public function getElementIocPrefix()
    {
        return 'layout.element';
    }

    /**
     * @param $code
     * @return string
     */
    public function getElementIocAlias($code)
    {
        return sprintf('%s.%s', $this->getElementIocPrefix(), $code);
    }

    /**
     * @param string|null $type
     * @return ElementInterface
     * @throws OutputException
     */
    protected function getElementInstance($name, $type = null, $elementData = array())
    {
        $alias = $this->getElementIocAlias($type ? strtolower($type) : 'default');
        
        if(!in_array($alias, $this->elementModels)) {
            throw new OutputException(sprintf('Incorrect element model: "%s"', $alias));
        }
        /** @var ElementInterface $instance */
        $instance = $this->app->make($alias, array($name, $elementData)); 
        
        return $instance;
    }

    /**
     * @param array $name
     * @param array $elementConfig
     * @return ElementInterface
     * @throws OutputException
     */
    protected function createElement($name, array $elementConfig)
    {
        $elementData = array();
        foreach ($elementConfig as $key => $value) {
            if (0 === strpos($key, '_')) {
                $elementData[substr($key, 1)] = $value;
            }
        }
        
        $type    = (isset($elementData['type']) ? $elementData['type'] : null);
        $element = $this->getElementInstance($name, $type, $elementData);

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
     * @param ElementInterface $parent
     * @return array
     * @throws OutputException
     */
    protected function generateElements(array $layoutConfig, ElementInterface $parent = null)
    {
        $elements = array();
        foreach ($layoutConfig as $name => $elementConfig) {
            if ($this->isValidElementName($name)) {
                $elements[$name] = $this->createElement($name, is_array($elementConfig) ? $elementConfig : array());
                if ($parent) {
                    $parent->addChild($elements[$name], $name);
                }
                $this->generateElements($elementConfig, $elements[$name]);
            }
        }
        
        return $elements;
    }

    /**
     * @param ConfigInterface $layoutConfig
     */
    protected function generateStructure(ConfigInterface $layoutConfig)
    {
        $this->rootElement = $this->createElement('root', []);
        $this->generateElements($layoutConfig->toArray(), $this->rootElement);
    }

    /**
     * @param ConfigInterface $layoutConfig
     * @return string
     * @throws OutputException
     */
    public function process(ConfigInterface $layoutConfig)
    {
        $this->generateStructure($layoutConfig);
        
        if (!$this->format instanceof FormatInterface) {
            throw new OutputException("Output format not defined");
        }
        
        return $this->format->format($this->format->processElement($this->rootElement));
    }
}

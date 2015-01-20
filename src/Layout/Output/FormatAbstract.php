<?php
/** {license_text}  */
namespace Layout\Output;

use Illuminate\Foundation\Application;
use Layout\Element\Output\OutputInterface;
use Layout\ElementInterface;

abstract class FormatAbstract
    implements FormatInterface
{
    protected $outputModels = [];
    protected $app;
    protected $prefix;

    /**
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->app          = $app;
        $this->prefix       = $this->getIocPrefix();
    }
    
    public function getOutputModels()
    {
        return $this->outputModels;
    }

    /**
     * @return string
     */
    public function getIocPrefix()
    {
        return sprintf('layout.element.output.%s', $this->getCode());
    }

    /**
     * @param $code
     * @return string
     */
    public function formatOutputModelAlias($code)
    {
        return sprintf('%s.%s', $this->getIocPrefix(), $code);
    }

    /**
     * @param string $code
     * @return string
     * @throws FormatException
     */
    public function getIocOutputModelAlias($code)
    {
        $alias = $this->formatOutputModelAlias($code);
        
        if(!in_array($alias, $this->outputModels)) {
            throw new FormatException(sprintf('Incorrect element output model: "%s"', $alias));
        }
        
        return $alias;
    }

    /**
     * @param $code
     */
    public function registerOutputModel($code)
    {
        $this->outputModels[] = $this->formatOutputModelAlias($code);;
    }

    /**
     * @param ElementInterface $element
     * @return mixed
     * @throws FormatException
     */
    public function processElement(ElementInterface $element)
    {
        $publicData = $element->getPublicData();
        $model      = $this->getIocOutputModelAlias($element['type'] ? strtolower($element['type']) : 'default');

        $children = array();
        if ($element->hasChild()) {
            foreach ($element->getChild() as $name => $childElement) {
                $children[$name] = $this->processElement($childElement);
            }
        }
        $publicData['children'] = $children;
        
        // Initialize output model
        return $this->getElementOutput($this->app->make($model, array($this, $publicData)));
    }
    
    abstract protected function getElementOutput(OutputInterface $elementOutputInterface);
}

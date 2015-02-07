<?php
/** {license_text}  */ 
namespace Layout\Element\Output;

use Core\Support\FluentInterface;
use Core\Support\FluentTrait;
use Layout\Element\Output\OutputInterface as ElementOutputInterface;

abstract class OutputAbstract
    implements ElementOutputInterface, FluentInterface
{
    use FluentTrait;
    
    protected $childOutputResult = array();
    protected $protectedAttributes = array();

    /**
     * clear instance
     */
    public function clear()
    {
        $this->attributes = [];
        $this->protectedAttributes = [];
        $this->childOutputResult = [];
    }

    /**
     * @param string $childName
     * @param $value
     */
    public function addChildOutputResult($childName, $value)
    {
        $this->childOutputResult[$childName] = $value;
    }

    /**
     * @param string|array|FluentInterface $key
     * @param null $value
     */
    public function setProtectedAttributes($key, $value = null)
    {
        if (is_array($key)) {
            foreach ($key as $dataKey => $dataValue) {
                $this->protectedAttributes[$dataKey] = $dataValue;
            }
        } else if (is_object($key) && method_exists($key, 'toArray')) {
            foreach ($key->toArray() as $dataKey => $dataValue) {
                $this->protectedAttributes[$dataKey] = $dataValue;
            }
        } else {
            $this->protectedAttributes[$key] = $value;
        }
        
    }

    /**
     * @param string|array|FluentInterface $key
     * @param null $value
     */
    public function setPublicData($key, $value = null)
    {
        if (is_array($key)) {
            $this->setAttributes($key);
        } else if (is_object($key) && method_exists($key, 'toArray')) {
            $this->setAttributes($key->toArray());
        } else {
            $this->{$key} = $value;
        }
    }
    
    /**
     * @param $key
     * @return null
     */
    protected function getProtectedAttribute($key)
    {
        return isset($this->protectedAttributes[$key]) ? $this->protectedAttributes[$key] : null;
    }

    /**
     * @param $method
     * @param $parameters
     * @return null
     */
    public function __call($method, $parameters)
    {
        return null;
    }

    /**
     * @return mixed
     */
    public final function processOutput()
    {
        $output = $this->getOutput();
        $this->clear();
        
        return $output;
    }
    
}

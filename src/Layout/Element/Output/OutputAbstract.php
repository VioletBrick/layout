<?php
/** {license_text}  */ 
namespace Layout\Element\Output;

use Layout\Support\FluentInterface;
use Layout\Support\FluentTrait;
use Layout\Element\Output\OutputInterface as ElementOutputInterface;

abstract class OutputAbstract
    implements ElementOutputInterface, FluentInterface
{
    use FluentTrait;
    
    protected $childOutputResult = array();
    protected $hiddenData = array();

    /**
     * @param $childName
     * @param $value
     */
    public function addChildOutputResult($childName, $value)
    {
        $this->childOutputResult[$childName] = $value;
    }

    /**
     * @param array|FluentInterface $data
     */
    public function setHiddenData($data)
    {
        if (is_array($data) || $data instanceof FluentInterface) {
            foreach ($data as $key => $value) {
                $this->hiddenData[$key] = $value;
            }
        }
    }
    
    /**
     * @param $key
     * @return null
     */
    protected function getHiddenData($key)
    {
        return isset($this->hiddenData[$key]) ? $this->hiddenData[$key] : null;
    }

    /**
     * @param array $data
     */
    public function setPublicData($data)
    {
        if (is_array($data) || $data instanceof FluentInterface) {
            $this->setAttributes($data);
        }
    }
    
    public function __call($method, $parameters)
    {
        return null;
    }
}

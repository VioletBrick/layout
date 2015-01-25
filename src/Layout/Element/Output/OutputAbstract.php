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

    /**
     * @param $childName
     * @param $value
     */
    public function addChildOutputResult($childName, $value)
    {
        $this->childOutputResult[$childName] = $value;
    }

    /**
     * @param array $data
     */
    public function setData($data)
    {
        if (!is_array($data) && !$data instanceof FluentInterface) {
            return;
        }
        foreach ($data as $key => $value) {
            $this->{$key} = $value;
        }
    }
    
    public function __call($method, $parameters)
    {
        return null;
    }
}

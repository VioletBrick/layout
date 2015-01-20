<?php
/** {license_text}  */ 
namespace Layout\Element\Output;

use Layout\Support\Fluent;
use Layout\Support\FluentInterface;
use Layout\Support\FluentTrait;
use Layout\Output\FormatInterface;
use Layout\Element\Output\OutputInterface as ElementOutputInterface;

abstract class OutputAbstract
    implements ElementOutputInterface, FluentInterface
{
    use FluentTrait;
    
    /** @var  FormatInterface  */
    protected $format;
    protected $children = array();

    /**
     * @param FormatInterface $format
     * @param array $data
     */
    public function __construct(FormatInterface $format, $data = array())
    {
        $this->setData($data);
        $this->format = $format;
    }

    /**
     * @param array $data
     */
    protected function setData(array $data)
    {
        foreach ($data as $key => $value) {
            $this->{$key} = $value;
        }
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return $this->prepareArray(array_merge($this->attributes, $this->children));
    }
    
    
    public function __call($method, $parameters)
    {
        return '';
    }
}

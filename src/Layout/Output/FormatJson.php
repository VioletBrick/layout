<?php
/** {license_text}  */

namespace Layout\Output;

use Layout\Element\Output\OutputInterface;

class FormatJson
    extends FormatAbstract
    implements FormatInterface
{
    /**
     * @return string
     */
    public function getCode()
    {
        return 'json';
    }
    
    /**
     * @param $data
     * @return array
     * @throws FormatException
     */
    public function format($data) {
        if (!is_array($data)) {
            throw new FormatException("Incorrect output data format");
        }
        
        return $data;
    }

    /**
     * @param OutputInterface $elementOutputInterface
     * @return mixed
     */
    protected function getElementOutput(OutputInterface $elementOutputInterface)
    {
        return $elementOutputInterface->toArray();
    }
}

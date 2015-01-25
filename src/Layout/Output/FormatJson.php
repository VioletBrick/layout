<?php
/** {license_text}  */

namespace Layout\Output;

class FormatJson
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
}

<?php
/** {license_text}  */

namespace Layout\Output;

class FormatHtml
    implements FormatInterface
{
    /**
     * @return string
     */
    public function getCode()
    {
        return 'html';
    }
    
    /**
     * @param $data
     * @return string
     * @throws FormatException
     */
    public function format($data) {
        if (!is_string($data)) {
            throw new FormatException("Incorrect output data format");
        }
        
        return $data;
    }
}

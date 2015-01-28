<?php
/** {license_text}  */
namespace Layout\Element\Output\Json;


class JsonIgnore
    extends JsonAbstract
{
    /**
     * @return array
     */
    public function toArray()
    {
        return array();
    }
}

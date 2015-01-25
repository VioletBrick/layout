<?php
/** {license_text}  */

namespace Layout\Element\Output\Json;

use Layout\Element\Output\OutputAbstract;

abstract class JsonAbstract
    extends OutputAbstract
    implements JsonInterface
{
    /**
     * @return array
     */
    public function getOutput()
    {
        return $this->toArray();
    }
    
    /**
     * @return array
     */
    public function toArray()
    {
        return $this->prepareArray(array_merge($this->attributes, $this->childOutputResult));
    }
}

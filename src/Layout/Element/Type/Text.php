<?php
/** {license_text}  */
namespace Layout\Element\Type;

class Text
    extends TypeAbstract
{
    protected function getPublicData()
    {
        return $this->fill(array(), $this, array(
            'text'
        ));
    }
}

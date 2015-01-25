<?php
/** {license_text}  */
namespace Layout\Element\Type;

class Template
    extends TypeAbstract
{
    protected function getPublicData()
    {
        return $this->fill(array(), $this, array(
            'template'
        ));
    }
}

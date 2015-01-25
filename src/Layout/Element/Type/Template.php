<?php
/** {license_text}  */
namespace Layout\Element\Type;

class Template
    extends TypeAbstract
{
    protected function getHiddenData()
    {
        return $this->fill(array(), $this, array(
            'template'
        ));
    }
}

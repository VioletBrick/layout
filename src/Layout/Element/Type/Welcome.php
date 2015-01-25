<?php
/** {license_text}  */
namespace Layout\Element\Type;

class Welcome
    extends TypeAbstract
{
    protected function getPublicData()
    {
        return $this->fill(array(), $this, array(
            'template',
            'welcome',
        ));
    }
}

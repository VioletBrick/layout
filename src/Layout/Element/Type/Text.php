<?php
namespace Layout\Element\Type;

use Layout\ElementAbstract;

/** {license_text}  */
class Text
    extends ElementAbstract
{
    protected function initialize(&$publicData)
    {
        $this->fill($publicData, $this, array(
            'text'
        ));
    }
}

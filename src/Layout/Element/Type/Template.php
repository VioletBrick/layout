<?php
namespace Layout\Element\Type;

use Layout\ElementAbstract;

/** {license_text}  */
class Template
    extends ElementAbstract
{
    protected function initialize(&$publicData)
    {
        $this->fill($publicData, $this, array(
            'template'
        ));
    }
}

<?php
namespace Layout\Element\Type;

use Layout\ElementAbstract;

/** {license_text}  */
class Welcome
    extends ElementAbstract
{
    protected function initialize(&$publicData)
    {
        $this->fill($publicData, $this, array(
            'template',
            'welcome',
        ));
    }
}

<?php
namespace Layout\Element;

use Layout\ElementAbstract;

/** {license_text}  */
class Text
    extends ElementAbstract
{
    public function render()
    {
        return sprintf('%s', $this['text']);
    }
}

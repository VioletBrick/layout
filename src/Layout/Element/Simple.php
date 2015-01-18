<?php
namespace Layout\Element;

use Layout\ElementAbstract;
use Layout\ElementInterface;

/** {license_text}  */
class Simple
    extends ElementAbstract
{
    /**
     * @return string
     */
    public function render()
    {
        $output = '';
        foreach ($this->getChild() as $element) {
            /** @var ElementInterface $element */
            $output = sprintf("%s%s", $output, $element->render());
        }
        
        return $output;
    }
}

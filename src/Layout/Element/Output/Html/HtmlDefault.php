<?php
/** {license_text}  */
namespace Layout\Element\Output\Html;

class HtmlDefault
    extends HtmlAbstract
{
    /**
     * @return $this|mixed
     */
    public function toHtml()
    {
        $output = '';
        if (!empty($this->childOutputResult)) {
            foreach ($this->childOutputResult as $name => $childOutput) {
                $output = sprintf('%s%s', $output, $childOutput);
            }
        }
        
        return $output;
    }
}

<?php
/** {license_text}  */

namespace Layout\Element\Output\Html;

class Text
    extends HtmlDefault
{
    /**
     * @return $this|mixed
     */
    public function toHtml()
    {
        return $this['text'];
    }
}

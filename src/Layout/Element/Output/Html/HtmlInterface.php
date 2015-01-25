<?php
/** {license_text}  */

namespace Layout\Element\Output\Html;

interface HtmlInterface
{
    public function toHtml();
    public function get($key, $default = null);
    public function __toString();
}

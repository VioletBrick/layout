<?php
/** {license_text}  */
namespace Layout\Element\Output\Html;

interface HtmlInterface
{
    /**
     * Retrieve render result
     * 
     * @return mixed
     */
    public function toHtml();

    /**
     * Get value
     * 
     * @param $key
     * @param null $default
     * @return mixed
     */
    public function get($key, $default = null);
}

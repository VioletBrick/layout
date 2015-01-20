<?php
/** {license_text}  */

namespace Layout\Element\Output\Html;

use Layout\Element\Output\OutputAbstract;

abstract class HtmlAbstract
    extends OutputAbstract
    implements HtmlInterface
{
    /**
     * @param string $key
     * @param null $default
     * @return array|mixed|string
     */
    public function get($key, $default = null)
    {
        $value = parent::get($key, $default);
        
        if (!$value) {
            if (isset($this->children[$key])) {
                $value = $this->children[$key];
            }
        }
        
        return $value;
    }
}

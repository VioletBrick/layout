<?php
/** {license_text}  */
namespace Layout\Element\Output\Html;

use Layout\Element\Output\OutputAbstract;

abstract class HtmlAbstract
    extends OutputAbstract
    implements HtmlInterface
{
    /**
     * @return string
     */
    public function getOutput()
    {
        return $this->toHtml();
    }

    /**
     * @param string $key
     * @param null $default
     * @return string
     */
    public function get($key, $default = null)
    {
        if (isset($this->childOutputResult[$key])) {
            return $this->childOutputResult[$key];
        }
        
        return parent::get($key, $default);
    }
}

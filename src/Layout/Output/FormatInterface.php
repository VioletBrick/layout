<?php
/** {license_text}  */

namespace Layout\Output;

use Layout\ElementInterface;

interface FormatInterface
{
    /**
     * @return string
     */
    public function getCode();

    /**
     * @return string
     */
    public function getIocPrefix();
    public function formatOutputModelAlias($code);
    public function getIocOutputModelAlias($code);
    public function format($data);
    public function processElement(ElementInterface $element);
    public function registerOutputModel($code);
}

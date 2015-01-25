<?php
/** {license_text}  */

namespace Layout\Output;

interface FormatInterface
{
    /**
     * @return string
     */
    public function getCode();

    /**
     * @param $data
     * @return mixed
     */
    public function format($data);
}

<?php
/** {license_text}  */ 
namespace Layout\Element\Output;

interface OutputInterface
{
    /**
     * @return mixed
     */
    public function toHtml();
    public function toArray();
}

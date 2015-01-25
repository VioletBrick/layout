<?php
/** {license_text}  */ 
namespace Layout\Element\Output;

interface OutputInterface
{
    public function setData($data);
    public function addChildOutputResult($childName, $value);
    public function getOutput();
}

<?php
/** {license_text}  */ 
namespace Layout\Element\Output;

interface OutputInterface
{
    public function setHiddenData($data);
    public function setPublicData($data);
    public function addChildOutputResult($childName, $value);
    public function getOutput();
}

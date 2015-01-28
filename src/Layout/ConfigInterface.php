<?php
/** {license_text}  */ 
namespace Layout;

interface ConfigInterface
{
    public function load($handles = [], $includeDefaultHandle = true);
    public function addConfigPath($path);
    public function toArray();
}

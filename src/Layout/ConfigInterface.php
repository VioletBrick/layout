<?php
namespace Layout;

/** {license_text}  */ 
interface ConfigInterface
{
    public function load($handles = [], $includeDefaultHandle = true);
    public function addConfigPath($path);
    public function toArray();
}

<?php
namespace Layout;

/** {license_text}  */ 
interface ConfigInterface
{
    public function load();
    public function addLoadPath($path);
    public function toArray();
}

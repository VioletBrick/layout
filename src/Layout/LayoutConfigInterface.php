<?php
namespace Layout;

/** {license_text}  */ 
interface LayoutConfigInterface
{
    public function load($handles = [], $includeDefaultHandle = true);
    public function addConfigPath($path);
    public function addTemplatePath($path);
    public function toArray();
    public function resolveConfigPath($fileName);
    public function resolveTemplatePath($fileName);
}

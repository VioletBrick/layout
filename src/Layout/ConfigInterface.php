<?php
namespace Layout;

/** {license_text}  */ 
interface ConfigInterface
{
    public function load();
    public function addConfigPath($path);
    public function addTemplatePath($path);
    public function toArray();
    public function resolveConfigPath($fileName);
    public function resolveTemplatePath($fileName);
}
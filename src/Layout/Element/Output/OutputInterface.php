<?php
/** {license_text}  */ 
namespace Layout\Element\Output;

interface OutputInterface
{
    /**
     * @param string|array|FluentInterface $key
     * @param null $value
     */
    public function setProtectedAttributes($key, $value = null);
    
    /**
     * @param string|array|FluentInterface $key
     * @param null $value
     */
    public function setPublicData($key, $value = null);

    /**
     * @param string $childName
     * @param $value
     */
    public function addChildOutputResult($childName, $value);

    /**
     * @return mixed
     */
    public function getOutput();

    /**
     * @return mixed
     */
    public function processOutput();
}

<?php
/** {license_text}  */
namespace Layout\Element\Type;

interface TypeInterface
{
    /**
     * @param DataTransportPublic $publicData
     * @param DataTransportProtected $protectedData
     * @param DataTransportChildren $childrenOutput
     * @return mixed
     */
    public function processOutput(DataTransportPublic $publicData, DataTransportProtected $protectedData, DataTransportChildren $childrenOutput);
}

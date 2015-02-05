<?php
/** {license_text}  */
namespace Layout\Element\Factory;

class FactoryJson
    extends FactoryAbstract
{
    const TYPE_CODE = 'json';

    protected function getTypeCode()
    {
        return self::TYPE_CODE;
    }
}

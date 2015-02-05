<?php
/** {license_text}  */
namespace Layout\Element\Factory;

class FactoryHtml
    extends FactoryAbstract
{
    const TYPE_CODE = 'html';
    
    protected function getTypeCode()
    {
        return self::TYPE_CODE;
    }
}

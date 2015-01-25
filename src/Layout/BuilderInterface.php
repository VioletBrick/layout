<?php
/** {license_text}  */
namespace Layout;

use Layout\Element\Factory\FactoryInterface;
use Layout\Element\Type\TypeInterface as ElementTypeInterface;

interface BuilderInterface
{
    /**
     * @param LayoutConfigInterface $layoutConfig
     * @param FactoryInterface $elementTypeFactory
     * @return ElementTypeInterface
     */
    public function buildStructure(LayoutConfigInterface $layoutConfig, FactoryInterface $elementTypeFactory);
}


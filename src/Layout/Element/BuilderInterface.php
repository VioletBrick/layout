<?php
/** {license_text}  */
namespace Layout\Element;

use Layout\Element\Factory\FactoryInterface;
use Layout\Element\Type\TypeInterface as ElementTypeInterface;
use Layout\LayoutConfigInterface;

interface BuilderInterface
{
    /**
     * @param LayoutConfigInterface $layoutConfig
     * @param FactoryInterface $elementTypeFactory
     * @return ElementTypeInterface
     */
    public function buildStructure(LayoutConfigInterface $layoutConfig, FactoryInterface $elementTypeFactory);
}


<?php
/** {license_text}  */
namespace Layout\Element\Factory;

use Layout\Element\Type\TypeInterface;

interface FactoryInterface 
{
    /**
     * @param string $abstract
     */
    public function registerDefaultModel($abstract);

    /**
     * @param string $abstract
     */
    public function registerDefaultOutputModel($abstract);
    
    /**
     * @param string $type
     * @param string $modelAbstract
     * @param string $outputModelAbstract
     */
    public function register($type, $modelAbstract, $outputModelAbstract = null);


    /**
     * @param $alias
     * @param array $parameters
     * @return TypeInterface
     */
    public function resolve($alias, array $parameters = array());
}

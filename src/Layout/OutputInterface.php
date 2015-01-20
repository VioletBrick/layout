<?php
/** {license_text}  */
namespace Layout;

use Illuminate\Foundation\Application;
use Layout\Output\FormatInterface;

interface OutputInterface
{
    /**
     * @param ConfigInterface $layoutConfig
     * @return mixed
     */
    public function process(ConfigInterface $layoutConfig);

    /**
     * @param $code
     */
    public function registerElementTypeModel($code);

    /**
     * @param FormatInterface $format
     */
    public function setFormat(FormatInterface $format);

    /**
     * @param string $code
     * @return mixed
     */
    public function getElementIocAlias($code);
}

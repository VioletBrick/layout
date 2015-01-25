<?php
/** {license_text}  */

namespace Layout\Element\Output\Html;

use Layout\LayoutConfig;
class Template
    extends HtmlDefault
{
    public function __construct(LayoutConfig $config)
    {
        $this->config = $config;
    }

    /**
     * @return $this|mixed|string
     */
    public function toHtml()
    {
        $output = '';
        if ($template = $this->getHiddenData('template')) {
            $template = $this->config->resolveTemplatePath($template);
            if ($template) {
                ob_start();
                require $template;
                $output = ob_get_clean();
            }
        }

        return $output;
    }
}

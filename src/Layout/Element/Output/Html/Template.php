<?php
/** {license_text}  */

namespace Layout\Element\Output\Html;

use Layout\LayoutConfig;
class Template
    extends HtmlDefault
{
    protected $template;
    
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
        if ($this->template) {
            $template = $this->config->resolveTemplatePath($this->template);
            if ($template) {
                ob_start();
                require $template;
                $output = ob_get_clean();
            }
        }

        return $output;
    }
}

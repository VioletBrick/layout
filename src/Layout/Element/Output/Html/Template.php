<?php
/** {license_text}  */

namespace Layout\Element\Output\Html;

use Layout\ConfigInterface;
use Layout\Output\FormatInterface;

class Template
    extends HtmlDefault
{
    protected $config;
    protected $template;
    
    public function __construct(FormatInterface $format, $data = array(), ConfigInterface $config)
    {
        parent::__construct($format, $data);
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

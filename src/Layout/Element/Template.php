<?php
namespace Layout\Element;

use Layout\ElementAbstract;

/** {license_text}  */
class Template
    extends ElementAbstract
{
    /**
     * @return string
     */
    public function render()
    {
        $output = '';
        if ($template = $this['template']) {
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

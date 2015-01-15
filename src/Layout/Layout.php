<?php
namespace Layout;

use Illuminate\Contracts\Events\Dispatcher as EventDispatcher;
use Illuminate\Support\Fluent;

/** {license_text}  */ 
class Layout
{
    protected $layoutConfig;
    protected $handles = array();
    
    /** @var EventDispatcher Illuminate\Contracts\Events\Dispatcher */
    protected $eventDispatcher;
    protected $config;
    
    public function __construct(EventDispatcher $dispatcher, Config $config)
    {
        $this->eventDispatcher = $dispatcher;
        $this->config          = $config;
    }
    
    protected function loadLayoutConfig()
    {
        $this->eventDispatcher->fire('layout.before_load_config', array($this));
        
        $this->config->load();

        $this->eventDispatcher->fire('layout.after_after_load_config', array($this));
        
        return $this;
    }

    protected function generateElements()
    {
        $this->eventDispatcher->fire('layout.before_generate_elements', array($this));

        $this->eventDispatcher->fire('layout.after_generate_elements', array($this));
    }
        
    
    public function load(array $handles = [], $useDefault = true)
    {
        if ($useDefault) {
            $handles[] = 'default';
        }
        
        $this->handles = $handles;

        $this->eventDispatcher->fire('layout.before_load', array($this));

        $this->loadLayoutConfig();
        $this->generateElements();

        $this->eventDispatcher->fire('layout.after_load', array($this));
        
        return $this;
    }
    

    public function render()
    {
        
    }
}

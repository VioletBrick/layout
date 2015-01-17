<?php
namespace Layout;

use Illuminate\Contracts\Events\Dispatcher as EventDispatcherInterface;
use Illuminate\Support\Fluent;

/** {license_text}  */ 
class Layout
    implements LayoutInterface
{
    protected $layoutConfig;
    protected $handles = array();
    
    /** @var  EventDispatcherInterface  */
    protected $eventDispatcher;
    /** @var  ConfigInterface  */
    protected $config;
    /** @var  RendererInterface */
    protected $renderer;

    /**
     * @param EventDispatcherInterface $dispatcher
     * @param ConfigInterface $config
     * @param RendererInterface $renderer
     */
    public function __construct(EventDispatcherInterface $dispatcher, ConfigInterface $config, RendererInterface $renderer)
    {
        $this->eventDispatcher = $dispatcher;
        $this->config          = $config;
    }

    /**
     * @return array
     */
    public function getHandles()
    {
        return $this->handles;
    }

    /**
     * @param $handle
     */
    public function addHandle($handle)
    {
        $this->handles[] = $handle;
    }

    /**
     * @param array $handles
     */
    public function setHandles(array $handles)
    {
        $this->handles = $handles;
    }

    /**
     * @return ConfigInterface
     */
    protected function loadConfig()
    {
        $this->eventDispatcher->fire('layout.before_load_config', array($this));
        
        $this->config->load();

        $this->eventDispatcher->fire('layout.after_after_load_config', array($this));
        
        return $this->config;
    }

    /**
     * @param array $handles
     * @param bool $useDefault
     * @return $this
     */
    public function load(array $handles = [], $useDefault = true)
    {
        if ($useDefault) {
            $handles[] = 'default';
        }
        
        $this->setHandles($handles);

        $this->eventDispatcher->fire('layout.before_renderer_prepare', array($this, $this->renderer));

        $this->renderer->prepare($this->loadConfig());

        $this->eventDispatcher->fire('layout.after_renderer_prepare', array($this, $this->renderer));
        
        return $this;
    }


    /**
     * @return mixed
     */
    public function render()
    {
        $this->eventDispatcher->fire('layout.before_render', array($this, $this->renderer));

        $this->renderer->prepare($this->loadConfig());

        $this->eventDispatcher->fire('layout.after_render', array($this, $this->renderer));

        return $this->renderer->render();
    }
}

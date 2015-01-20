<?php
namespace Layout;

use Illuminate\Contracts\Events\Dispatcher as EventDispatcherInterface;
use Layout\Output\FormatInterface;

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
    /** @var  OutputInterface */
    protected $output;

    /**
     * @param EventDispatcherInterface $dispatcher
     * @param ConfigInterface $config
     * @param OutputInterface $output
     */
    public function __construct(EventDispatcherInterface $dispatcher, ConfigInterface $config, OutputInterface $output)
    {
        $this->eventDispatcher = $dispatcher;
        $this->config          = $config;
        $this->output          = $output;
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
        
        $this->config->load($this->getHandles());

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

        return $this;
    }

    /**
     * @param FormatInterface $format
     * @param string|array $handles
     * @param bool $useDefault
     * @return mixed
     */
    public function process(FormatInterface $format, $handles = [], $useDefault = true)
    {
        if ($handles && !is_array($handles)) {
            $handles = [$handles];
        }
        if ($useDefault) {
            $handles[] = 'default';
        }

        $this->setHandles($handles);
        
        $this->output->setFormat($format);
        
        return $this->output->process($this->loadConfig());
    }
}

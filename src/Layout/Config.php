<?php
/** {license_text}  */
namespace Layout;

use Symfony\Component\Yaml\Parser as Parser;

/** {license_text}  */
class Config
    implements ConfigInterface
{
    const ACTION_REWRITE  = 'rewrite';
    const ACTION_REMOVE   = 'remove';
    
    protected $actions = array(self::ACTION_REWRITE, self::ACTION_REMOVE);

    protected $systemNodes  = array();
    
    /** @var  Parser */
    protected $parser;
    protected $schema;
    
    protected $configPath   = array();
    protected $globalData   = array();
    protected $data         = array();
    protected $handles      = array();
    
    protected $container;

    public function __construct(Parser $parser)
    {
        $this->parser = $parser;
    }
    
    /**
     * @param string $path
     * @return $this
     */
    public function addConfigPath($path)
    {
        if (is_dir($path)) {
            array_unshift($this->configPath, $path);
        }
        
        return $this;
    }

    /**
     * @param array $target
     * @param array $data
     * @return array
     */
    protected function arrayMerge(array $target, array $data)
    {
        foreach ($data as $key => $value) {
            if (isset($target[$key])) {
                if (in_array($key, $this->actions)) {
                    $target[$key] = array_merge((array) $target[$key], (array) $value);
                } else if (is_array($target[$key]) || is_array($value)) {
                    $target[$key] = $this->arrayMerge((array) $target[$key], (array) $value);
                } else {
                    $target[$key] = $value;
                }
            } else {
                $target[$key] = $value;
            }
        }
        
        return $target;
    }

    /**
     * Unset removed nodes
     */
    protected function applyRemoves()
    {
        foreach ($this->globalData as $key => $value) {
            if (self::ACTION_REMOVE == $key) {
                foreach((array)$value as $path) {
                    $path = explode('/', $path);
                    $config = &$this->globalData;
                    while ($name = array_shift($path)) {
                        if (empty($path)) {
                            unset($config[$name]);
                            continue 2;
                        } else {
                            if (isset($config[$name])) {
                                $config = &$config[$name];
                            } else {
                                continue 2;
                            }
                        }
                    }
                }
                unset($this->globalData[$key]);
            }
        }
    }

    /**
     * Rewrite value by path
     */
    protected function applyRewrites()
    {
        foreach ($this->globalData as $key => $value) {
            if (self::ACTION_REWRITE == $key) {
                foreach((array) $value as $path => $data) {
                    $path = explode('/', $path);
                    $config = &$this->globalData;
                    while ($name = array_shift($path)) {
                        if (empty($path)) {
                            $config[$name] = $data;
                            continue 2;
                        } else {
                            if (isset($config[$name])) {
                                $config = &$config[$name];
                            } else {
                                continue 2;
                            }
                        }
                    }
                }
                unset($this->globalData[$key]);
            }
        }
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return $this->data;
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
     * @param array $handles
     * @param bool $includeDefaultHandle
     * @return $this
     */
    public function load($handles = [], $includeDefaultHandle = true)
    {
        if ($handles && !is_array($handles)) {
            $handles = [$handles];
        }
        if ($includeDefaultHandle) {
            $handles[] = 'default';
        }

        $data        = array();
        $loadedFiles = array();
        
        foreach ($this->configPath as $path) {
            foreach (scandir($path) as $fileName) {
                $filePath = "{$path}/{$fileName}";
                if (is_file($filePath)){
                    if (!in_array($fileName, $loadedFiles)) {
                        $data          = $this->arrayMerge($data, (array) $this->parser->parse(file_get_contents($filePath)));
                        $loadedFiles[] = $fileName;
                    }
                }
            }
        }
        
        foreach ($data as $handle => $handleData) {
            if (in_array($handle, $handles)) {
                if (empty($this->globalData)) {
                    $this->globalData = $handleData;
                } else {
                    $this->globalData = $this->arrayMerge($this->globalData, $handleData);
                }
            }
        }
        
        $this->applyRewrites();
        $this->applyRemoves();

        $this->globalData = array_shift($this->globalData);
        
        $this->data = $this->globalData;
        
        return $this;
    }

    /**
     * @param string $target
     */
    public function setTarget($target)
    {
        if ($target) {
            $path = explode('/', $target);
            if (!empty($path)) {
                $data = $this->globalData;
                while ($elementName = array_shift($path)) {
                    if (isset($data[$elementName])) {
                        $data = $data[$elementName];
                    } else {
                        $data = null;
                        break;
                    }
                    
                }
                $this->data = $data;
            }
        }
    }
}

<?php
/** {license_text}  */

namespace Layout;

use Illuminate\Contracts\Events\Dispatcher as EventDispatcher;
use Symfony\Component\Yaml\Parser as Parser;

/** {license_text}  */
class LayoutConfig
    implements LayoutConfigInterface
{
    const CONFIG_NODE_ACTION = 'action';
    const CONFIG_NODE_EXTEND = 'extend';
    const CONFIG_NODE_UNSET  = 'remove';

    protected $systemNodes  = array();
    
    /** @var  YamlLoader */
    protected $parser;
    protected $schema;
    
    protected $configFiles  = array();
    protected $configPath   = array();
    protected $templatePath = array();
    protected $data         = array();
    protected $handles      = array();

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
        array_unshift($this->configPath, $path);
        
        return $this;
    }

    /**
     * @param string $path
     * @return $this
     */
    public function addTemplatePath($path)
    {
        array_unshift($this->templatePath, $path);

        return $this;
    }

    /**
     * @param string $name
     */
    public function registerConfigFile($name)
    {
        $this->configFiles[] = $name;
    }

    /**
     * @param string $fileName
     * @return bool|string
     */
    public function resolveConfigPath($fileName)
    {
        foreach ($this->configPath as $path) {
            $filePath = "{$path}/{$fileName}";
            if (is_file($filePath) && is_readable($filePath)) {
                return realpath($filePath);
            }
        }

        return false;
    }

    /**
     * @param string $fileName
     * @return bool|string
     */
    public function resolveTemplatePath($fileName)
    {
        foreach ($this->templatePath as $path) {
            $filePath = "{$path}/{$fileName}";
            if (is_file($filePath) && is_readable($filePath)) {
                return realpath($filePath);
            }
        }

        return false;
    }

    /**
     * @param $fileName
     * @return array
     */
    protected function loadFile($fileName)
    {
        if ($filePath = $this->resolveConfigPath($fileName)) {
            return $this->parser->parse(file_get_contents($filePath));
        }
        
        return array();
    }

    /**
     * @param array $target
     * @param array $data
     * @return array
     */
    protected function arrayMerge(array $target, array $data)
    {
        foreach ($data as $key => $value) {
            if (is_array($value) && isset($target[$key]) && is_array($target[$key])) {
                $target[$key] = $this->arrayMerge($target[$key], $value);
            } else {
                $target[$key] = $value;
            }
        }
        
        return $target;
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
        
        $data = array();
        
        foreach ($this->configFiles as $file) {
            $data = $this->arrayMerge($data, $this->loadFile($file));
        }
        
        foreach ($data as $handle => $handleData) {
            if (in_array($handle, $handles)) {
                if (empty($this->data)) {
                    $this->data = $handleData;
                } else {
                    $this->data = $this->arrayMerge($this->data, $handleData);
                }
                
            }
        }
        
        return $this;
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
}

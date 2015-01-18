<?php
/** {license_text}  */

namespace Layout;

use Illuminate\Contracts\Events\Dispatcher as EventDispatcher;
use Symfony\Component\Yaml\Parser as Parser;

/** {license_text}  */
class Config
    implements ConfigInterface
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
     * @return $this
     */
    protected function loadFile($fileName)
    {
        if ($filePath = $this->resolveConfigPath($fileName)) {
            $data = $this->parser->parse(file_get_contents($filePath));
            $this->data = array_merge($this->data, $data);
        }

        return $this;
    }

    /**
     * @return $this
     */
    public function load()
    {
        foreach ($this->configFiles as $file) {
            $this->loadFile($file);
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
}

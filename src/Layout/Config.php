<?php
/** {license_text}  */

namespace Layout;

use Illuminate\Contracts\Events\Dispatcher as EventDispatcher;
use Illuminate\Support\Fluent;
use RomaricDrigon\MetaYaml\MetaYaml;
use RomaricDrigon\MetaYaml\Loader\YamlLoader;

/** {license_text}  */
class Config
    implements ConfigInterface
{
    /** @var  YamlLoader */
    protected $loader;
    protected $schema;
    
    protected $configFiles = array();
    protected $loadPaths = array();
    protected $data = array();

    public function __construct(YamlLoader $loader)
    {
        $schema = $loader->loadFromFile(__DIR__ . "/../../etc/schema.yml");

        $this->loader = $loader;
        $this->schema = new MetaYaml($schema, true);
    }
    
    /**
     * @param string $path
     * @return $this
     */
    public function addLoadPath($path)
    {
        array_unshift($this->loadPaths, $path);
        
        return $this;
    }

    /**
     * @param string $fileName
     * @return bool|string
     */
    protected function resolvePath($fileName)
    {
        foreach ($this->loadPaths as $path)
        {
            $filePath = "{$path}/{$fileName}";
            if (is_file($filePath) && is_readable($filePath)) {
                return $filePath;
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
        if ($filePath = $this->resolvePath($fileName)) {
            $data = $this->loader->loadFromFile($fileName);
            if ($this->schema->validate($data)) {
                $this->data = array_merge($this->data, $data);
            }
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
